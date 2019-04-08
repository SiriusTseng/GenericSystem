<?php
/**
 * Created by PhpStorm.
 * User: Sirius
 * Date: 2019/3/6
 * Time: 14:10
 */

namespace App\Admin\Controllers\User;


use App\Http\Controllers\Controller;
use App\Model\Driver;
use App\Model\User;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;
use Illuminate\Support\Facades\DB;
use mysql_xdevapi\Exception;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    use HasResourceActions;

    /**
     * Index interface.
     *
     * @param Content $content
     * @return Content
     */
    public function index(Content $content)
    {
        return $content
            ->header('用户')
            ->description('列表')
            ->body($this->grid());
    }

    /**
     * Show interface.
     *
     * @param mixed $id
     * @param Content $content
     * @return Content
     */
    public function show($id, Content $content)
    {
        return $content
            ->header('用户')
            ->description('详情')
            ->body($this->detail($id));
    }

    /**
     * Edit interface.
     *
     * @param mixed $id
     * @param Content $content
     * @return Content
     */
    public function edit($id, Content $content)
    {
//        abort(404);
        return $content
            ->header('用户')
            ->description('编辑')
            ->body($this->form()->edit($id));
    }


    /**
     * Create interface.
     *
     * @param Content $content
     * @return Content
     */
    public function create(Content $content)
    {
        abort(404);
    }

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new User());

        $grid->id('ID')->sortable();
        $grid->column('avatar', '头像')->display(function ($value) {
            return "<img src='{$value}' style='height: 25px;' alt='加载失败'/>";
        });
        $grid->roles('角色')->pluck('name')->label();
        $grid->column('nickname', '用户昵称');
        $grid->column('truename', '真实姓名')->editable('text');
        $grid->column('miniprogram_open_id', '小程序openid');
        $grid->column('sex', '性别');

        $grid->column('phone', '手机号');


        $grid->created_at('创建时间');
        $grid->updated_at('更新时间');

        return $grid;
    }

    /**
     * Make a show builder.
     *
     * @param mixed $id
     * @return Show
     */
    protected function detail($id)
    {
        $show = new Show(User::findOrFail($id));

        $show->id('ID');
        $show->created_at('创建时间');
        $show->updated_at('更新时间');

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new User());

        $form->display('id', 'ID');
        $form->display('nickname', '昵称');
        $form->text('truename', '真实姓名');
        $form->image('avatar', '头像')->disable();
        $form->display('sex', '性别');

        $form->multipleSelect('roles', trans('admin.roles'))
            ->options(Role::all()->sortByDesc('id')->pluck('name', 'id'))
            ->help("<span class='label label-warning'>请勿多选</span>&nbsp;&nbsp;<span class='label label-warning'>司机配置请到司机管理中配置</span>")
            ->rules("max:1", ['max' => '最多只能选择一个角色']);
        $form->multipleSelect('permissions', trans('admin.permissions'))->options(Permission::all()->pluck('name', 'id'));

        $form->display('created_at', '创建时间');
        $form->display('updated_at', '更新时间');

        $form->saving(function (Form $form) {
            $role = Role::findByName('司机');
            if ($form->model()->hasRole($role)) {
                if (isset($form->model()->driver->status) && $form->model()->driver->status != -1) {
                    throw new \Exception('司机非空闲状态,无法更变为其他角色');
                }

                if (!in_array(1, $form->roles)) {
                    $form->model()->removeRole($role);
                    $form->model()->driver()->delete();
                }
            }
        });

        $form->saved(function (Form $form) {
//           if ($form->model()->role)
        });
        return $form;
    }
}
