# phpMyAdmin MySQL-Dump
# version 2.3.3pl1
# http://www.phpmyadmin.net/ (download page)
# --------------------------------------------------------
#
# Table structure for table `cquotes`
#

CREATE TABLE cquotes (
  id int(11) NOT NULL auto_increment,
  quote varchar(255) NOT NULL default '',
  client varchar(255) NOT NULL default '',
  curl varchar(255) NOT NULL default '',
  PRIMARY KEY  (id),
  KEY id (id)
) TYPE=MyISAM;

