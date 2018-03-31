<?php
/**
 * @var \Dvelum\Lang $lang
 */
$lang = $this->get('lang');
$data = $this->get('data');
?>
<style>
.resultCls td{
    font-size: 13px;
    font-family: Tahoma;
    color: #000000;
}
</style>
<table class="resultCls">
    <tr>
        <td><?php echo $lang->get('result_success'); ?></td>
        <td><?php echo $data['success']; ?></td>
    </tr>
    <tr>
        <td><?php echo $lang->get('result_error'); ?></td>
        <td><?php echo $data['success']; ?></td>
    </tr>
    <tr>
        <td><?php echo $lang->get('result_new'); ?></td>
        <td><?php echo $data['success']; ?></td>
    </tr>
    <tr>
        <td><?php echo $lang->get('result_update'); ?></td>
        <td><?php echo $data['success']; ?></td>
    </tr>
    <tr>
        <td><?php echo $lang->get('result_time'); ?></td>
        <td><?php echo $data['success']; ?></td>
    </tr>
</table>
