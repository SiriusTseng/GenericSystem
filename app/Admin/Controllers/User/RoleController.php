<?php
/**
 * Created by PhpStorm.
 * User: Sirius
 * Date: 2019/3/6
 * Time: 14:10
 */

namespace App\Admin\Controllers\User;


use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Column;
use Encore\Admin\Layout\Content;
use Encore\Admin\Layout\Row;
use Encore\Admin\Show;
use Encore\Admin\Widgets\Box;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
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
            ->header('角色')
            ->description('列表')
            ->row(function (Row $row) {
                $row->column(8, $this->grid());
                $row->column(4, function (Column $column) {
                    $form = new \Encore\Admin\Widgets\Form();

                    $form->action(admin_base_path('user/roles'));
                    $form->tools(function (Form\Tools $tools) {
                        $tools->disableList();
                        $tools->disableView();
                        $tools->disableDelete();
                    });

                    $form->footer(function (Form\Footer $footer) {
                        $footer->disableReset();
                        $footer->disableViewCheck();
                        $footer->disableEditingCheck();
                        $footer->disableCreatingCheck();
                    });

                    $form->text('name', '角色名称');
                    $form->multipleSelect('permissions', '拥有权限')->options(Permission::all()->pluck('name', 'id'));

                    $column->append((new Box('创建', $form))->style('primary'));
                });
            });
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
            ->header('角色')
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
        return $content
            ->header('角色')
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
        return $content
            ->header('角色')
            ->description('创建')
            ->body($this->form());
    }

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Role());

        $grid->disableCreateButton();


        $grid->column('name', '角色名称');

        $grid->column('permissions', '角色权限')->display(function () {
            return $this->permissions->pluck('name');
        })->label();

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
        $show = new Show(Role::findOrFail($id));

        $show->field('id', '编号');
        $show->field('name', '角色名称');
        $show->field('permissions', '角色权限')->as(function ($value) {
            return $value->pluck('name');
        })->label();
        $show->field('created_at', '创建时间');
        $show->field('updated_at', '更新时间');

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new Role());


        $form->tools(function (Form\Tools $tools) {
            $tools->disableList();
            $tools->disableView();
            $tools->disableDelete();
        });

        $form->footer(function (Form\Footer $footer) {
            $footer->disableReset();
            $footer->disableViewCheck();
            $footer->disableEditingCheck();
            $footer->disableCreatingCheck();
        });

        $form->text('name', '角色名称');
        $form->multipleSelect('permissions', '拥有权限')->options(Permission::all()->pluck('name', 'id'));


        return $form;
    }
}
