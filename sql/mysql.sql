CREATE TABLE ugm_page_cate (
  `csn` smallint(5) unsigned NOT NULL auto_increment,
  `of_csn` smallint(5) unsigned NOT NULL default '0',
  `title` varchar(255) NOT NULL default '',
  `sort` smallint(5) unsigned NOT NULL default '0',
  `enable` enum('1','0') NOT NULL default '1',
  `type` tinyint(3) unsigned NOT NULL default '0',
  PRIMARY KEY  (`csn`)
) ENGINE=MyISAM;


CREATE TABLE ugm_page_main (
  `msn` smallint(5) unsigned NOT NULL auto_increment,
  `csn` smallint(5) unsigned NOT NULL default '0',
  `title` varchar(255) NOT NULL default '',
  `content` text NOT NULL default '',
  `start_time` varchar(255) NOT NULL default '',
  `end_time` varchar(255) NOT NULL default '',
  `enable` enum('1','0') NOT NULL default '1',
  `counter` smallint(5) unsigned NOT NULL default '0',
  `top` enum('0','1') NOT NULL default '0',
  `date` varchar(255) NOT NULL default '',
  `uid` smallint(5) unsigned NOT NULL default '0',
  `sort` smallint(5) unsigned NOT NULL default '0',
  `editor` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`msn`)
) ENGINE=MyISAM;

CREATE TABLE  `ugm_page_menu` (
  `menu_sn` smallint(5) unsigned NOT NULL auto_increment,
  `menu_type` tinyint unsigned NOT NULL default '0' ,
  `menu_ofsn` smallint unsigned NOT NULL default '0' ,
  `menu_sort` smallint(5) unsigned NOT NULL default '0',
  `menu_title` varchar(255) NOT NULL default '',
  `menu_op` varchar(255) NOT NULL default '',
  `menu_tip` varchar(255) NOT NULL default '',
  `menu_enable` enum('1','0') NOT NULL default '1',
  `menu_new` enum('0','1') NOT NULL default '0',
  `menu_url` varchar(255) NOT NULL default '',
  `menu_date` varchar(255) NOT NULL default '',
  `menu_uid` smallint(5) unsigned NOT NULL default '0',
  PRIMARY KEY  (`menu_sn`)
) ENGINE=MyISAM;

CREATE TABLE `ugm_page_files_center` (
  `files_sn` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `col_name` varchar(255) NOT NULL default '',
  `col_sn` smallint(5) unsigned NOT NULL default '0',
  `sort` smallint(5) unsigned NOT NULL default '0',
  `kind` enum('img','file') NULL ,
  `file_name` varchar(255) NOT NULL default '',
  `file_type` varchar(255) NOT NULL default '',
  `file_size` int(10) unsigned NOT NULL default '0',
  `description` text NOT NULL default '',
  `counter` mediumint(8) unsigned NOT NULL default '0',
  PRIMARY KEY (`files_sn`)
) ENGINE=MyISAM;

CREATE TABLE `ugm_page_var` (
  `sn` smallint(5) unsigned NOT NULL AUTO_INCREMENT comment 'sn',
  `csn` smallint(5) unsigned NOT NULL default '0' comment '類別',
  `sort` smallint(5) unsigned NOT NULL default '0' comment '排序',
  `enable` enum('1','0') NOT NULL default '1' comment '啟用',
  `var_title` varchar(255) NOT NULL  default '' comment '標題',
  `var_name` varchar(255) NOT NULL  default '' comment '變數名稱',
  `var_value` text NOT NULL  default '' comment '變數值',
  `var_tip` varchar(255) NOT NULL  default '' comment '提示',
  PRIMARY KEY (`sn`)
) ENGINE=MyISAM;

