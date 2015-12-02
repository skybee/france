<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html>
    <head>
        <title>Donor Admin</title>
        <link rel="stylesheet" type="text/css" href="/css/donor_admin.css" />
    </head>
    
    <body>
        <div id="head">Donor Admin</div>
        <div id="add_domain">
            <form action="/donor_admin/add_domain/" method="post" >
                Sub:
                <input type="text" name="subname" />
                Domain:
                <input type="text" name="name" />
                Host:
                <input type="text" name="hosting" />
                Account:
                <input type="text" name="account" />
                <input type="submit" name="add" value="Добавить" /> 
            </form>
        </div>
    </body>
</html>

