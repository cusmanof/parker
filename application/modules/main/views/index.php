<script type="text/javascript">
    function doconfirm()
    {
        job = confirm("Are you sure you want to remove your entries?");
        if (job != true)
        {
            return false;
        }
    }

    function showMessage(ele)
    {
        var dd = ele.id;
        if (dd) {
             var xhttp = new XMLHttpRequest();
                xhttp.open("GET", "main?act=single&day=" + dd , false);
                xhttp.send();
        }

    }

</script>   

<?php
//flash data
if (!empty($this->session->flashdata('error'))) {
    echo '<div class="alert alert-warning">  <strong>Warning!</strong>';
    echo $this->session->flashdata('error');
    echo '</div>';
}
echo '<h2>' . $data['user'] . '</h2>';
echo $this->falendar->show($data);
// Generate calendar
//    $tt = str_replace('{base_url}', base_url(), $this->calendar->generate($data->year, $data->month, $data->content));
//    $tt = str_replace('{year}', $data->year, $data->tt);
//    $tt = str_replace('{month}', $data->month, $data->tt);
//    echo $tt;
//flash data
echo '<p>';
if (!empty($this->session->flashdata('error'))) {
    echo '<div class="alert alert-warning">  <strong>Warning!</strong>';
    echo $this->session->flashdata('error');
    echo '</div>';
}
?>
