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
/******************************************************************************
 * Function: client_quotes_show
 * Input   : void
 * Output  : $quote: Text of the quote
             $client: Client
             $curl: Client's URL

 ******************************************************************************/
function client_quotes_show() {
    global $xoopsDB;
    $block = array();
    $result = $xoopsDB->query("SELECT quote, client, curl FROM ".$xoopsDB->prefix("cquotes")." ORDER BY RAND() LIMIT 1");
    list($quote, $client, $curl)= $xoopsDB->fetchRow($result);
    $block['quote']=$quote;
    $block['client']=$client;
    $block['curl']=$curl;
    return $block;
}
?>