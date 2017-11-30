function backup_transactions() {
    $.ajax({
        type: "POST",
        url: '../triggerbackup.php',
        data:{action:'triggerTheBackup'},
        success:function(html) {
            alert(html);
        }

    });
}