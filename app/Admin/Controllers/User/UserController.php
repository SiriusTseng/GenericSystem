<?php
/**
 * Created by PhpStorm.
 * User: Sirius
 * Date: 2019/3/6
 * Time: 14:10
 */

namespace App\Admin\Controllers\User;


use App\Http\Controllers\Controller;
use App\Models\User;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;
use Field\Interaction\FieldSubscriberTrait;
use Field\Interaction\FieldTriggerTrait;

use Field\Interaction\SubscribeScriptBuilder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    use HasResourceActions,FieldSubscriberTrait,FieldTriggerTrait;

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
        return $content
            ->header('用户')
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
        $grid = new Grid(new User());

        $grid->id('ID')->sortable();
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

        $form->ignore(['password_confirmation ']);
        $form->display('id', 'ID');
        $form->email('email','邮箱');
        $form->text('username','用户名');
        $form->password('password','密码')->rules('confirmed',['confirmed'=>'两次密码输入不相同']);
        $form->password('password_confirmation ','重复密码');

        $form->multipleSelect('roles', trans('admin.roles'))
            ->options(Role::all()->sortByDesc('id')->pluck('name', 'id'));
        $form->multipleSelect('permissions', trans('admin.permissions'))->options(Permission::all()->pluck('name', 'id'));

        $form->display('created_at', '创建时间');
        $form->display('updated_at', '更新时间');

        $form->saving(function (Form $form) {
        });

        $form->saved(function (Form $form) {
//           if ($form->model()->role)
        });

        $triggerScript=$this->createTriggerScript($form);
        $subscribeScript=$this->createSubscriberScript($form,function (SubscribeScriptBuilder $builder){
            $builder->subscribe('email','input',function ($event){

                //abc@abc.com
                return <<<EOT
    function(data){
        $('.username').val(data.split('@',1));
    }
EOT;
            });
        });
        $form->scriptinjecter('name_no_care', $triggerScript, $subscribeScript);
        return $form;
    }
}
