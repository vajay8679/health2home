<?php

namespace App\Admin\Controllers;

use App\Models\VendorAppSetting;
use App\Models\UserType;
use App\Models\CommissionSetting;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class VendorAppSettingController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Vendor App Settings';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new VendorAppSetting);

        $grid->column('id', __('Id'));
        $grid->column('app_name', __('Application Name'));
        $grid->column('app_logo', __('App Logo'))->image();
        $grid->column('default_currency', __('Default Currency'));
        $grid->column('order_commission', __('Order Commission'));
        $grid->column('user_type', __('User Type'))->display(function($user_types){
            $user_types = UserType::where('id',$user_types)->value('user');
            return $user_types;
        });
        $grid->disableExport();
        $grid->disableCreation();
        $grid->disableFilter();
        $grid->actions(function ($actions) {
            $actions->disableView();
            $actions->disableDelete();
        });
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
        $show = new Show(VendorAppSetting::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('application_name', __('Application name'));
        $show->field('logo', __('Logo'));
        $show->field('email', __('Email'));
        $show->field('default_currency', __('Currency symbol'));
        $show->field('user_type', __('User Type'));

        $show->field('created_at', __('Created at'));
        $show->field('updated_at', __('Updated at'));

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new VendorAppSetting);
        $user_types = UserType::pluck('user','id');
        $commission_types = CommissionSetting::pluck('commission_type','id');

        $form->text('app_name', __('Application Name'))->rules(function ($form) {
            return 'required|max:100';
        });
        $form->image('app_logo', __('Logo'))->rules('required')->rules('required')->uniqueName();
        $form->textarea('address', __('Address'))->rules(function ($form) {
            return 'required';
        });
        $form->text('default_currency', __('Currency Symbol'))->rules(function ($form) {
            return 'required';
        });
        $form->text('currency_short_code', __('Currenct Short Code'))->rules(function ($form) {
            return 'required';
        });
        
        $form->decimal('order_commission', __('Order Commission'))->rules(function ($form) {
            return 'required';
        });
        $form->textarea('description', __('Description'))->rules(function ($form) {
            return 'required';
        });
        $form->text('app_version', __('App Version'))->rules(function ($form) {
            return 'required';
        });
        $form->select('user_type', __('User Type'))->options($user_types)->rules(function ($form) {
            return 'required';
        });
        $form->select('commission_type', __('Commission Type'))->options($commission_types)->rules(function ($form) {
            return 'required';
        });

        $form->tools(function (Form\Tools $tools) {
            $tools->disableDelete(); 
            $tools->disableView();
        });
        $form->footer(function ($footer) {
            $footer->disableViewCheck();
            $footer->disableEditingCheck();
            $footer->disableCreatingCheck();
        });
        return $form;
    }
}
