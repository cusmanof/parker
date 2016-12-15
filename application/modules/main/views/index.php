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
                xhttp.open("GET", "main?act=select&day=" + dd , false);
                xhttp.send();
                window.location.reload();
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
if (!empty($this->session->flashdata('msg'))) {
    echo '<div class="alert alert-info">';
    echo $this->session->flashdata('msg');
    echo '</div>';
}
echo $this->falendar->show($data);
//flash data
echo '<p>';
if (!empty($this->session->flashdata('error'))) {
    echo '<div class="alert alert-warning">  <strong>Warning!</strong>';
    echo $this->session->flashdata('error');
    echo '</div>';
}
?>
