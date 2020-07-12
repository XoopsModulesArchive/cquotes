<?php
//  ------------------------------------------------------------------------ //
//                      Random Client Quotes Module for                      //
//               XOOPS - PHP Content Management System 2.0                   //
//                          Michael Gonzalez                                 //
//                       http://www.truwire.com                              //
//                            Version 1.0.0                                  //
//                        Copyright (c) 2006                                 //
//    Derived from Random Quotes by Mario Figge @ www.zona84.com             //
// ------------------------------------------------------------------------- //
include_once "admin_header.php";

$op = "list";

if (isset($HTTP_GET_VARS)) {
    foreach ($HTTP_GET_VARS as $k => $v) {
        $$k = $v;
    }
}

if (isset($HTTP_POST_VARS)) {
    foreach ($HTTP_POST_VARS as $k => $v) {
        $$k = $v;
    }
}

if ($op == "list") {
    // List quote in database, and form for add new.
    $myts =& MyTextSanitizer::getInstance();
    xoops_cp_header();

    echo "
    <h4 style='text-align:left;'>"._CQ_TITLE."</h4>
    <form action='index.php' method='post'>
    <table border='0' cellpadding='0' cellspacing='0' width='100%'><tr><td class='bg2'>
    <table width='100%' border='0' cellpadding='4' cellspacing='1'>
    <tr class='bg3' align='center'><td align='left'>"._CQ_QUOTE."</td><td>"._CQ_CLIENT."</td><td>"._CQ_CURL."</td><td>&nbsp;</td></tr>";
    $result = $xoopsDB->query("SELECT * FROM ".$xoopsDB->prefix("cquotes"));
    $count = 0;
    while ( list($id, $quote, $client, $curl) = $xoopsDB->fetchRow($result) ) {
        $quote=$myts->makeTboxData4Edit($quote);
        $client=$myts->makeTboxData4Edit($client);
        $curl=$myts->makeTboxData4Edit($curl);
        echo "<tr class='bg1'><td align='left'>
            <input type='hidden' value='$id' name='id[]' />
            <input type='hidden' value='$quote' name='oldquote[]' />
            <textarea name='newquote[]' rows='2'>$quote</textarea>
            </td>
        <td align='center'>
            <input type='hidden' value='$client' name='oldclient[]' />
            <input type='text' value='$client' name='newclient[]' maxlength='255' size='20' />
        </td>
        <td align='center'>
            <input type='hidden' value='$curl' name='oldcurl[]' />
            <input type='text' value='$curl' name='newcurl[]' maxlength='255' size='30' />
        </td>
        <td nowrap='nowrap' align='right'><a href='index.php?op=del&amp;id=".$id."&amp;ok=0'>"._DELETE."</a></td></tr>";
        $count++;
    }
    if ($count > 0) {
        echo "<tr align='center' class='bg3'><td colspan='4'><input type='submit' value='"._SUBMIT."' /><input type='hidden' name='op' value='edit' /></td></tr>";
    }
    echo "</table></td></tr></table></form>
    <br /><br />
    <h4 style='text-align:left;'>"._CQ_ADD."</h4>
    <form action='index.php' method='post'>
    <table border='0' cellpadding='0' cellspacing='0' width='100%'>
        <tr>
        <td class='bg2'>
            <table width='100%' border='0' cellpadding='4' cellspacing='1'>
                <tr nowrap='nowrap'>
                <td class='bg3'>"._CQ_CLIENT." </td>
                <td class='bg1'>
                    <input type='text' name='client' size='30' maxlength='255' />
                </td></tr>
                <tr nowrap='nowrap'>
                <td class='bg3'>"._CQ_CURL." </td>
                <td class='bg1'>
                    <input type='text' name='curl' size='30' maxlength='255' />
                </td></tr>
                <tr nowrap='nowrap'>
                <td class='bg3'>"._CQ_QUOTE." </td>
                <td class='bg1'>
                    <textarea name='quote' cols='20' rows='3'></textarea>
                </td></tr>
                <tr>
                <td class='bg3'>&nbsp;</td>
                <td class='bg1'>
                    <input type='hidden' name='op' value='add' />
                    <input type='submit' value='"._SUBMIT."' />
                </td></tr>
            </table>
        </td></tr>
    </table>
    </form>";

    xoops_cp_footer();
    exit();
}

if ($op == "add") {
    // Add quote
    $myts =& MyTextSanitizer::getInstance();
    $client = $myts->makeTboxData4Save($client);
    $quote = $myts->makeTboxData4Save($quote);
    $curl = $myts->makeTboxData4Save($curl);
    $newid = $xoopsDB->genId($xoopsDB->prefix("cquotes")."id");
    $sql = "INSERT INTO ".$xoopsDB->prefix("cquotes")." (id, client, quote, curl) VALUES (".$newid.", '".$client."', '".$quote."', '".$curl."')";
    if (!$xoopsDB->query($sql)) {
        xoops_cp_header();
        echo "Could not add category";
        xoops_cp_footer();
    } else {
        redirect_header("index.php?op=list",1,_XD_DBSUCCESS);
    }
    exit();
}

if ($op == "edit") {
    // Edit quotes
    $myts =& MyTextSanitizer::getInstance();
    $count = count($newclient);
    for ($i = 0; $i < $count; $i++) {
        if ( $newclient[$i] != $oldclient[$i] || $newquote[$i] != $oldquote[$i] || $newcurl[$i] != $oldcurl[$i]) {
            $client = $myts->makeTboxData4Save($newclient[$i]);
            $quote = $myts->makeTboxData4Save($newquote[$i]);
            $curl = $myts->makeTboxData4Save($newcurl[$i]);
            $sql = "UPDATE ".$xoopsDB->prefix("cquotes")." SET client='".$client."',quote='".$quote."',curl='".$curl."' WHERE id=".$id[$i]."";
            $xoopsDB->query($sql);
        }
    }
    redirect_header("index.php?op=list",1,_XD_DBSUCCESS);
    exit();
}

if ($op == "del") {
    // Delete quote
    if ($ok == 1) {
        $sql = "DELETE FROM ".$xoopsDB->prefix("cquotes")." WHERE id = ".$id ;
        if (!$xoopsDB->query($sql)) {
            xoops_cp_header();
            echo "Could not delete category";
            xoops_cp_footer();
        } else {
            redirect_header("index.php?op=list",1,_XD_DBSUCCESS);
        }
        exit();
    } else {
        xoops_cp_header();
        xoops_confirm(array('op' => 'del', 'id' => $id, 'ok' => 1), 'index.php', _CQ_SUREDEL);
        xoops_cp_footer();
        exit();
    }
}

?>