<?php

// $Id: pntables.php,v 1.1 2006/02/22 00:35:54 mikhail Exp $
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
// Purpose of file:  Table information for template module
// ----------------------------------------------------------------------

/**
 * This function is called internally by the core whenever the module is
 * loaded.  It adds in the information
 */
function flashChat_pntables()
{
    $pntable = [];

    $bans = pnConfigGetVar('prefix') . '_bans';

    $pntable['bans'] = $bans;

    $pntable['bans_column'] = [
        'created' => $bans . '.created',
'userid' => $bans . '.userid',
'banneduserid' => $bans . '.banneduserid',
'roomid' => $bans . '.roomid',
'ip' => $bans . '.ip',
    ];

    $connections = pnConfigGetVar('prefix') . '_connections';

    $pntable['connections'] = $connections;

    $pntable['connections_column'] = [
        'id' => $connections . '.id',
'updated' => $connections . '.updated',
'created' => $connections . '.created',
'userid' => $connections . '.userid',
'roomid' => $connections . '.roomid',
'state' => $connections . '.state',
'color' => $connections . '.color',
'start' => $connections . '.start',
'lang' => $connections . '.lang',
'ip' => $connections . '.ip',
'tzoffset' => $connections . '.tzoffset',
    ];

    $ignors = pnConfigGetVar('prefix') . '_ignors';

    $pntable['ignors'] = $ignors;

    $pntable['ignors_column'] = [
        'created' => $ignors . '.created',
'userid' => $ignors . '.userid',
'ignoreduserid' => $ignors . '.ignoreduserid',
    ];

    $messages = pnConfigGetVar('prefix') . '_messages';

    $pntable['messages'] = $messages;

    $pntable['messages_column'] = [
        'id' => $messages . '.id',
'created' => $messages . '.created',
'toconnid' => $messages . '.toconnid',
'touserid' => $messages . '.touserid',
'toroomid' => $messages . '.toroomid',
'command' => $messages . '.command',
'userid' => $messages . '.userid',
'roomid' => $messages . '.roomid',
'txt' => $messages . '.txt',
    ];

    $rooms = pnConfigGetVar('prefix') . '_rooms';

    $pntable['rooms'] = $rooms;

    $pntable['rooms_column'] = [
        'id' => $rooms . '.id',
'updated' => $rooms . '.updated',
'created' => $rooms . '.created',
'name' => $rooms . '.name',
'ispublic' => $rooms . '.ispublic',
'ispermanent' => $rooms . '.ispermanent',
    ];

    // Return the table information

    return $pntable;
}
