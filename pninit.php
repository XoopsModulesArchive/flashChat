<?php

// $Id: pninit.php,v 1.1 2006/02/22 00:35:54 mikhail Exp $
// ----------------------------------------------------------------------
// POST-NUKE Content Management System
// Copyright (C) 2002 by the PostNuke Development Team.
// http://www.postnuke.com/
// ----------------------------------------------------------------------
// Based on:
// PHP-NUKE Web Portal System - http://phpnuke.org/
// Thatware - http://thatware.org/
// ----------------------------------------------------------------------
// LICENSE
//
// This program is free software; you can redistribute it and/or
// modify it under the terms of the GNU General Public License (GPL)
// as published by the Free Software Foundation; either version 2
// of the License, or (at your option) any later version.
//
// This program is distributed in the hope that it will be useful,
// but WIthOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// To read the license please visit http://www.gnu.org/copyleft/gpl.html
// ----------------------------------------------------------------------
// Original Author of file: Jim McDonald
// Purpose of file:  Initialisation functions for template
// ----------------------------------------------------------------------

/**
 * initialise the template module
 * This function is only ever called once during the lifetime of a particular
 * module instance
 */
function flashChat_init()
{
    $dbhost = pnConfigGetVar('dbhost');

    $dbuser = pnConfigGetVar('dbuname');

    $dbpass = pnConfigGetVar('dbpass');

    $dbbase = pnConfigGetVar('dbname');

    $dbpref = pnConfigGetVar('prefix');

    //Write the system configuration

    $filename = __DIR__ . '/inc/config.srv.php';

    if ($handle = fopen($filename, 'w+b')) {
        $str = "<?php\n";

        $str .= "\t\$GLOBALS['config']['db'] = array(\n";

        $str .= "\t\t'host' => '$dbhost',\n";

        $str .= "\t\t'user' => '$dbuser',\n";

        $str .= "\t\t'pass' => '$dbpass',\n";

        $str .= "\t\t'base' => '$dbbase',\n";

        $str .= "\t\t'pref' => '$dbpref" . "_',\n";

        $str .= "\t);\n";

        $str .= '?>';

        if (fwrite($handle, $str)) {
            fclose($handle);
        } else {
            pnSessionSetVar('errormsg', "Could not write to '$filename' file");

            return false;
        }
    } else {
        pnSessionSetVar('errormsg', "Could not open '$filename' file for writing");

        return false;
    }

    [$dbconn] = pnDBGetConn();

    $pntable = pnDBGetTables();

    $banstable = $pntable['bans'];

    $sql = "CREATE TABLE $banstable (
		created timestamp(6) NOT NULL,
		userid int(11) default NULL,
		banneduserid int(11) default NULL,
		roomid int(11) default NULL,
		ip varchar(16) default NULL,
		KEY userid (userid),
		KEY created (created)
	)";

    $dbconn->Execute($sql);

    if (0 != $dbconn->ErrorNo()) {
        pnSessionSetVar('errormsg', _CREATETABLEFAILED);

        return false;
    }

    $connectionstable = $pntable['connections'];

    $sql = "CREATE TABLE $connectionstable (
		id varchar(32) NOT NULL default '',
		updated timestamp(6) NOT NULL,
		created timestamp(6) NOT NULL,
		userid int(11) default NULL,
		roomid int(11) default NULL,
		state tinyint(4) NOT NULL default '1',
		color int(11) default NULL,
		start int(11) default NULL,
		lang char(2) default NULL,
		ip varchar(16) default NULL,
		tzoffset int(11) default '0',
		PRIMARY KEY  (id),
		KEY userid (userid),
		KEY roomid (roomid),
		KEY updated (updated)
	)";

    $dbconn->Execute($sql);

    if (0 != $dbconn->ErrorNo()) {
        pnSessionSetVar('errormsg', _CREATETABLEFAILED);

        return false;
    }

    $ignorstable = $pntable['ignors'];

    $sql = "CREATE TABLE $ignorstable (
		created timestamp(6) NOT NULL,
		userid int(11) default NULL,
		ignoreduserid int(11) default NULL,
		KEY userid (userid),
		KEY ignoreduserid (ignoreduserid),
		KEY created (created)
	)";

    $dbconn->Execute($sql);

    if (0 != $dbconn->ErrorNo()) {
        pnSessionSetVar('errormsg', _CREATETABLEFAILED);

        return false;
    }

    $messagestable = $pntable['messages'];

    $sql = "CREATE TABLE $messagestable (
		id int(11) NOT NULL auto_increment,
		created timestamp(6) NOT NULL,
		toconnid varchar(32) default NULL,
		touserid int(11) default NULL,
		toroomid int(11) default NULL,
		command varchar(255) NOT NULL default '',
		userid int(11) default NULL,
		roomid int(11) default NULL,
		txt text,
		PRIMARY KEY  (id),
		KEY touserid (touserid),
		KEY toroomid (toroomid),
		KEY toconnid (toconnid),
		KEY created (created)
	)";

    $dbconn->Execute($sql);

    if (0 != $dbconn->ErrorNo()) {
        pnSessionSetVar('errormsg', _CREATETABLEFAILED);

        return false;
    }

    $roomstable = $pntable['rooms'];

    $sql = "CREATE TABLE $roomstable (
		id int(11) NOT NULL auto_increment,
		updated timestamp(6) NOT NULL,
		created timestamp(6) NOT NULL,
		name varchar(32) NOT NULL default '',
		ispublic char(1) default NULL,
		ispermanent char(1) default NULL,
		PRIMARY KEY  (id),
		KEY name (name),
		KEY ispublic (ispublic),
		KEY ispermanent (ispermanent),
		KEY updated (updated)
	)";

    $dbconn->Execute($sql);

    if (0 != $dbconn->ErrorNo()) {
        pnSessionSetVar('errormsg', _CREATETABLEFAILED);

        return false;
    }

    $rms = ['The Lounge', 'Hollywood', 'Tech Talk', 'Current Events'];

    for ($i = 0, $iMax = count($rms); $i < $iMax; $i++) {
        $sql = "INSERT INTO $roomstable (created, name, ispublic, ispermanent) VALUES (NOW(), '{$rms[$i]}', 'y', '" . ($i + 1) . "')";

        $dbconn->Execute($sql);
    }

    // Initialisation successful

    return true;
}

/**
 * upgrade the template module from an old version
 * This function can be called multiple times
 * @param mixed $oldversion
 * @return false
 * @return false
 */
function flashChat_upgrade($oldversion)
{
    return false;
}

/**
 * delete the template module
 * This function is only ever called once during the lifetime of a particular
 * module instance
 */
function flashChat_delete()
{
    [$dbconn] = pnDBGetConn();

    $pntable = pnDBGetTables();

    foreach (['bans', 'connections', 'ignors', 'messages', 'rooms'] as $table) {
        $sql = "DROP TABLE {$pntable[$table]}";

        $dbconn->Execute($sql);

        if (0 != $dbconn->ErrorNo()) {
            // Report failed deletion attempt

            return false;
        }
    }

    // Deletion successful

    return true;
}
