<?php
/**
 * 2019-11-15 By max
 * max@ithou.cn
 */
namespace service;
// 基本用法
// $form = LayForm::form([
//     LayForm::select('操作', 'type', [
//         LayForm::option('请选择'),
//         LayForm::option('增加', 'inc'),
//         LayForm::option('扣除', 'dec'),
//     ], ['required' => true, 'lay-verify' => 'required']),
//     LayForm::text('金币', 'gold', '', ['placeholder' => '金币数量', 'required' => true, 'lay-verify' => 'required|number']),
//     LayForm::text('备注', 'remark', '', ['placeholder' => '备注信息，如：后台充值/扣除']),
//     LayForm::hidden('uid', $uid),
//     LayForm::submit('确定提交', ['lay-filter' => 'SubBtn']),
// ]);
class LayForm {

    /**
     * 生成表单
     *
     * @param array 表单组件集合
     * @param array 表单额外参数
     * @param string 表单类型 默认form表单
     * @return void
     */
    public static function form ($table = [], $param = [], $form =  'form') {
        $html = '';
        $html .= '<' . $form . ' class="layui-form" '; // layui-form-pane 表单框框样式
        $html .= self::param($param);
        $html .= '>';
        if (is_array($table) && $table) {
            foreach ($table as $v) {
                $html .= $v;
            }
        }
        $html .= '</' . $form . '>';
        return $html;
    }

    /**
     * 文本域
     *
     * @param [type] 提示标题
     * @param [type] 提交name名
     * @param [type] 提交值
     * @param array 额外参数
     * @return void
     */
    public static function textarea ($title, $name, $value = null, $param = []) {
        $html = '';
        $html .= '<div class="layui-form-item">';
        $html .= '<label class="layui-form-label">' . $title . '</label>';
        $html .= '<div class="layui-input-block">';
        $html .= '<textarea name="' . $name . '" class="layui-textarea" ';
        $html .= self::param($param);
        $html .= '>' . $value . '</textarea>';
        $html .= '</div>';
        $html .= '</div>';
        return $html;
    }
    
    /**
     * 文本框
     *
     * @param [type] 提示标题
     * @param [type] 提交name名
     * @param [type] 提交值
     * @param array 额外参数
     * @return void
     */
    public static function text ($title, $name, $value = null, $param = []) {
        $html = '';
        $html .= '<div class="layui-form-item">';
        $html .= '<label class="layui-form-label">' . $title . '</label>';
        $html .= '<div class="layui-input-block">';
        $html .= '<input type="text" name="' . $name . '" value="' . $value . '" class="layui-input" ';
        $html .= self::param($param);
        $html .='>';
        $html .= '</div>';
        $html .= '</div>';
        return $html;
    }

    /**
     * 编辑器
     *
     * @param [type] 提示标题
     * @param [type] 提交name名
     * @param [type] 提交值
     * @param array 编辑器参数 如工具栏设置 array('hideTool' => array('image', 'help')) 可参考文档 @https://www.layui.com/doc/modules/layedit.html#set
     * @return void
     */
    public static function editor ($title, $name, $value = null, $editorParam = []) {
        $html = '';
        $html .= '<div class="layui-form-item">';
        $html .= '<label class="layui-form-label">' . $title . '</label>';
        $html .= '<div class="layui-input-block">';
        $html .= '<textarea id="editor_' . $name . '" name="' . $name . '" lay-verify="editor_' . $name . '" class="layui-textarea">' . $value . '</textarea>';
            // 编辑器设置
            $html .= '<script>';
            $html .= 'layui.use(["layedit", "form"], function(){ var layedit = layui.layedit, form = layui.form;';
                // 全局设置
                if (is_array($editorParam) && $editorParam) {
                    $html .= 'layedit.set({';
                    foreach ($editorParam as $k => $v) {
                        $html .= '"' . $k . '":';
                        if (is_array($v)) {
                            $html .= '{';
                            foreach ($v as $key => $val) {
                                $html .= '"' . $key . '":"' . $val . '",';
                            }
                            $html = rtrim($html, ',') . '}';
                        } else {
                            $html .= '"' . $v . '"';
                        }
                    }
                    $html .= '});';
                }
                // 建立编辑器
                $html .= 'var editor_index_' . $name . ' = layedit.build("editor_' . $name . '");';    
                $html .= 'form.verify({';
                    $html .= 'editor_' . $name . ': function(value) { ';
                        $html .= 'return layedit.sync(editor_index_' . $name . ');';
                    $html .= '}';
                $html .= '});';
            $html .= '});';
            $html .= '</script>';
        $html .= '</div>';
        $html .= '</div>';
        return $html;
    }
    
    /**
     * 上传组件
     *
     * @param [type] 标题
     * @param [type] input的接收name
     * @param [type] 接收值
     * @param array 上传参数 如上传接口等 array('url' => 'xxx/xxx') @https://www.layui.com/doc/modules/upload.html#options
     * @return void
     */
    public static function upload ($title, $name, $value = null, $uploadParam = []) {
        $html = '';
        $html .= '<div class="layui-form-item">';
        $html .= '<label class="layui-form-label">' . $title . '</label>';
        $html .= '<div class="layui-input-block">';
        $html .= '<button type="button" class="layui-btn" id="upload_' . $name . '">';
        $html .= '<i class="layui-icon layui-icon-upload"></i>点击上传</button>';
        $html .= '<input type="hidden" name="' . $name . '" value="' . $value . '">'; // 上传图片隐藏域
        $html .= '<span id="previewbox_' . $name . '">';
        if ($value) {
            $html .= '<a href="' . $value . '" target="_blank" title="点击查看大图">';
            $html .= '<img style="width: 38px; height: 38px; margin-left: 5px;" src="' . $value . '" />'; // 效果预览
            $html .= '</a>';
        }
        $html .= '</span>';
        // 上传参数
        $html .= '<script>';
            $html .= 'layui.use(["upload"], function(){ var upload = layui.upload, $ = layui.$;';
                $html .= 'upload.render({ "elem": "#upload_' . $name . '"';
                    if (is_array($uploadParam) && $uploadParam) {
                        foreach ($uploadParam as $k => $v) {
                            $html .= ',"' . $k . '":';
                            if (is_array($v)) {
                                $html .= '{';
                                foreach ($v as $key => $val) {
                                    $html .= '"' . $key . '":"' . $val . '",';
                                }
                                $html = rtrim($html, ',') . '}';
                            } else {
                                $html .= '"' . $v . '"';
                            }
                        }
                    }
                    // 完成回调处理
                    $html .= ',done: function (res) {
                        $("input[name=\'' . $name . '\']").val(res.data.url);
                        var ' . $name . '_html = \'<a href="\' + res.data.url + \'" target="_blank" title="点击查看大图">\';
                        ' . $name . '_html += \'<img style="width: 38px; height: 38px; margin-left: 5px;" src="\' + res.data.url + \'" />\';
                        ' . $name . '_html += \'</a>\';
                        $("#previewbox_' . $name . '").html(' . $name . '_html);
                    }';
                $html .= '})';
            $html .= '})';
        $html .= '</script>';
        $html .= '</div>';
        $html .= '</div>';
        return $html;
    }
    
    /**
     * 时间选择框
     *
     * @param [type] 提示标题
     * @param [type] 提交name名
     * @param [type] 提交值
     * @param array input额外参数
     * @param array 日期组件参数 如设置默认时间等 array('value' => '2018-08-18') 可参考手册 @https://www.layui.com/doc/modules/laydate.html#value
     * @return void
     */
    public static function time ($title, $name, $value = null, $param = [], $dateParam = []) {
        $html = '';
        $html .= '<div class="layui-form-item">';
        $html .= '<label class="layui-form-label">' . $title . '</label>';
        $html .= '<div class="layui-input-block">';
        $html .= '<input type="text" id="laydate_' . $name . '" name="' . $name . '" value="' . $value . '" class="layui-input" ';
        $html .= self::param($param);
        $html .='>';
        $html .= '<script>';
            $html .= 'layui.use(["laydate"], function(){ var laydate = layui.laydate;';
                $html .= 'laydate.render({ "elem": "#laydate_' . $name . '"';
                    if (is_array($dateParam) && $dateParam) {
                        foreach ($dateParam as $k => $v) {
                            $html .= ',"' . $k . '":';
                            if (is_array($v)) {
                                $html .= '{';
                                foreach ($v as $key => $val) {
                                    $html .= '"' . $key . '":"' . $val . '",';
                                }
                                $html = rtrim($html, ',') . '}';
                            } else {
                                $html .= '"' . $v . '"';
                            }
                        }
                    }
                $html .= '})';
            $html .= '})';
        $html .= '</script>';
        $html .= '</div>';
        $html .= '</div>';
        return $html;
    }

    /**
     * 密码框
     *
     * @param [type] 提示标题
     * @param [type] 提交name名
     * @param [type] 提交值
     * @param array input额外参数
     * @return void
     */
    public static function password ($title, $name, $value = null, $param = []) {
        $html = '';
        $html .= '<div class="layui-form-item">';
        $html .= '<label class="layui-form-label">' . $title . '</label>';
        $html .= '<div class="layui-input-block">';
        $html .= '<input type="password" name="' . $name . '" value="' . $value . '" class="layui-input" ';
        $html .= self::param($param);
        $html .='>';
        $html .= '</div>';
        $html .= '</div>';
        return $html;
    }
    
    /**
     * 隐藏域
     *
     * @param [type] 提交name名
     * @param [type] 提交值
     * @param array input额外参数
     * @return void
     */
    public static function hidden ($name, $value = null, $param = []) {
        $html = '';
        $html .= '<input type="hidden" name="' . $name . '" value="' . $value . '"';
        $html .= self::param($param);
        $html .='>';
        return $html;
    }

    /**
     * 下拉框
     *
     * @param [type] 提示标题
     * @param [type] 提交name名
     * @param [type] option标签
     * @param array 额外参数
     * @return void
     */
    public static function select ($title, $name, $options = null, $param = []) {
        $html = '';
        $html .= '<div class="layui-form-item">';
        $html .= '<label class="layui-form-label">' . $title . '</label>';
        $html .= '<div class="layui-input-block">';
        $html .= '<select class="layui-menuselect" name="' . $name . '" ';
        $html .= self::param($param);
        $html .= '>';
        if (is_array($options)) {
            foreach ($options as $v) {
                $html .= $v;
            }
        } else {
            $html .= $options;
        }
        $html .= '</select>';
        $html .= '</div>';
        $html .= '</div>';
        return $html;
    }

    /**
     * option标签
     *
     * @param [type] 提示标题
     * @param [type] 提交值
     * @param array 额外参数
     * @return void
     */
    public static function option ($title, $value = null, $param = []) {
        $html = '';
        $html .= '<option value="' . (!isNull($value) ? $value : $title) . '"';
        $html .= self::param($param);
        $html .= '>' . $title . '</option>';
        return $html;
    }
    
    /**
     * 选项组
     * [ // 第一组
     *  'label' => '组1标题', 'option' => [
     *          ['title' => '选项1标题', 'value' => '选项1值', 'param' => ['selected' => true]],
     *          ['title' => '选项2标题', 'value' => '选项2值']
     *     ],
     * ],
     * [ // 第二组
     *  'label' => '组2标题', 'option' => [
     *          ['title' => '选项1标题', 'value' => '选项1值', 'param' => ['selected' => true]],
     *          ['title' => '选项2标题', 'value' => '选项2值']
     *     ],
     * ]
     * @param array 选项组
     * @return void
     */
    public static function optgroup ($optgroup = [], $placeholder = '请选择') {
        $html = '<option value="">' . $placeholder . '</option>';
        if (is_array($optgroup) && $optgroup) {
            foreach ($optgroup as $v) {
                $html .= '<optgroup label="' . $v['label'] . '">';
                    if (is_array($v['option']) && $v['option']) {
                        foreach ($v['option'] as $val) {
                            $html .= '<option value="' . (!isNull($val['value']) ? $val['value'] : $val['title']) . '"';
                            if (isset($val['param'])) {
                                $html .= self::param($val['param']);
                            }
                            $html .= '>' . $val['title'] . '</option>';
                        }
                    }
                $html .= '</optgroup>';
            }
        }
        return $html;
    }

    /**
     * 开关
     *
     * @param [type] 提示标题
     * @param [type] 提交name名
     * @param [type] 选中状态 checked
     * @param array 额外参数
     * @return void
     */
    public static function switch ($title, $name, $checked = null, $param = []) {
        $html = '';
        $html .= '<div class="layui-form-item" pane>';
        $html .= '<label class="layui-form-label">' . $title . '</label>';
        $html .= '<div class="layui-input-block">';
        $html .= '<input type="checkbox" name="' . $name . '" lay-skin="switch" ' . $checked . ' value="1" ';
        $html .= self::param($param);
        $html .= '>';
        $html .= '</div>';
        $html .= '</div>';
        return $html;
    }
    
    /**
     * 复选框
     *
     * @param [type] 提示标题
     * @param array 选中状态 checked
     * @return void
     */
    public static function checkbox ($title, $checkbox = []) {
        $html = '';
        $html .= '<div class="layui-form-item" pane>';
        $html .= '<label class="layui-form-label">' . $title . '</label>';
        $html .= '<div class="layui-input-block">';
        if (is_array($checkbox) && $checkbox) {
            foreach ($checkbox as $v) {
                $html .= '<input type="checkbox" name="' . $v['name'] . '" value="' . ($v['value'] ? $v['value'] : $v['name']) . '" title="' . $v['title'] . '" ';
                if (isset($v['param']) && is_array($v['param'])) {
                    $html .= self::param($v['param']);
                }
                $html .= '>';
            }
        }
        $html .= '</div>';
        $html .= '</div>';
        return $html;
    }

    /**
     * 单选按钮
     *
     * @param [type] 提示标题
     * @param array radio设置数组
     * [
     *      ['name' => '提交名, 如:sex', 'value' => '提交值, 如: 1', 'title' => 'radio提示, 如:男', 'param' => [input额外参数数组]], // 第一组
     *      ['name' => '提交名, 如:sex', 'value' => '提交值, 如: 0', 'title' => 'radio提示, 如:女', 'param' => [input额外参数数组]] // 第二组
     * ]
     * @return void
     */
    public static function radio ($title, $radio = []) {
        $html = '';
        $html .= '<div class="layui-form-item" pane>';
        $html .= '<label class="layui-form-label">' . $title . '</label>';
        $html .= '<div class="layui-input-block">';
        if (is_array($radio) && $radio) {
            foreach ($radio as $v) {
                $html .= '<input type="radio" name="' . $v['name'] . '" value="' . ($v['value'] ? $v['value'] : $v['name']) . '" title="' . $v['title'] . '" ';
                if (isset($v['param']) && is_array($v['param'])) {
                    $html .= self::param($v['param']);
                }
                $html .= '>';
            }
        }
        $html .= '</div>';
        $html .= '</div>';
        return $html;
    }

    /**
     * 普通按钮
     *
     * @param string 按钮名
     * @param array 按钮额外参数
     * @return void
     */
    public static function button ($title = '提交保存', $param = []) {
        $html = '';
        $html .= '<div class="layui-form-item">';
        $html .= '<div class="layui-input-block">';
        $html .= '<button class="layui-btn" type="button" ';
        $html .= self::param($param);
        $html .='>' . $title . '</button>';
        $html .= '</div>';
        $html .= '</div>';
        return $html;
    }

    /**
     * 提交按钮
     *
     * @param string 按钮名
     * @param array 按钮额外参数
     * @return void
     */
    public static function submit ($title = '提交保存', $param = []) {
        $html = '';
        $html .= '<div class="layui-form-item">';
        $html .= '<div class="layui-input-block">';
        $html .= '<button class="layui-btn" lay-submit ';
        $html .= self::param($param);
        $html .='>' . $title . '</button>';
        $html .= '</div>';
        $html .= '</div>';
        return $html;
    }
    
    /**
     * 组合参数
     *
     * @param [type] 额外参数
     * @return void
     */
    protected static function param ($param) {
        $html = '';
        if (is_array($param) && $param) {
            foreach ($param as $k => $v) {
                if ($k == 'single') {
                    $html .= $v . ' ';
                } else {
                    $html .= $k . '="' . $v . '" ';
                }
            }
        }
        return $html;
    }
}