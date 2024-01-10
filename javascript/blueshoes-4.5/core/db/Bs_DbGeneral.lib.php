<?php/********************************************************************************************
* BlueShoes Framework; This file is part of the php application framework.
* NOTE: This code is stripped (obfuscated). To get the clean documented code goto 
*       www.blueshoes.org and register for the free open source *DEVELOPER* version or 
*       buy the commercial version.
*       
*       In case you've already got the developer version, then this is one of the few 
*       packages/classes that is only available to *PAYING* customers.
*       To get it go to www.blueshoes.org and buy a commercial version.
* 
* @copyright www.blueshoes.org
* @author    Samuel Blume <sam at blueshoes dot org>
* @author    Andrej Arn <andrej at blueshoes dot org>
*/?><?php
function bs_isReservedDbWord($word) {$word = strToLower(trim($word));static $discourage = array('action', 'bit', 'date', 'enum', 'no', 'text', 'time', 'timestamp');if (in_array($word, $discourage)) {return 1;}
static $disallowed = array (
'action',           'add',              'aggregate',        'all',
'alter',            'after',            'and',              'as',
'asc',              'avg',              'avg_row_length',   'auto_increment',
'between',          'bigint',           'bit',              'binary',
'blob',             'bool',             'both',             'by',
'cascade',          'case',             'char',             'character',
'change',           'check',            'checksum',         'column',
'columns',          'comment',          'constraint',       'create',
'cross',            'current_date',     'current_time',     'current_timestamp',
'data',             'database',         'databases',        'date',
'datetime',         'day',              'day_hour',         'day_minute',
'day_second',       'dayofmonth',       'dayofweek',        'dayofyear',
'dec',              'decimal',          'default',          'delayed',
'delay_key_write',  'delete',           'desc',             'describe',
'distinct',         'distinctrow',      'double',           'drop',
'end',              'else',             'escape',           'escaped',
'enclosed',         'enum',             'explain',          'exists',
'fields',           'file',             'first',            'float',
'float4',           'float8',           'flush',            'foreign',
'from',             'for',              'full',             'function',
'global',           'grant',            'grants',           'group',
'having',           'heap',             'high_priority',    'hour',
'hour_minute',      'hour_second',      'hosts',            'identified',
'ignore',           'in',               'index',            'infile',
'inner',            'insert',           'insert_id',        'int',
'integer',          'interval',         'int1',             'int2',
'int3',             'int4',             'int8',             'into',
'if',               'is',               'isam',             'join',
'primary',          'keys',             'kill',             'last_insert_id',
'leading',          'left',             'length',           'like',
'lines',            'limit',            'load',             'local',
'lock',             'logs',             'long',             'longblob',
'longtext',         'low_priority',     'max',              'max_rows',
'match',            'mediumblob',       'mediumtext',       'mediumint',
'middleint',        'min_rows',         'minute',           'minute_second',
'modify',           'month',            'monthname',        'myisam',
'natural',          'numeric',          'no',               'not',
'null',             'on',               'optimize',         'option',
'optionally',       'or',               'order',            'outer',
'outfile',          'pack_keys',        'partial',          'password',
'precision',        'primary',          'procedure',        'process',
'processlist',      'privileges',       'read',             'real',
'references',       'reload',           'regexp',           'rename',
'replace',          'restrict',         'returns',          'revoke',
'rlike',            'row',              'rows',             'second',
'select',           'set',              'show',             'shutdown',
'smallint',         'soname',           'sql_big_tables',   'sql_big_selects',
'sql_low_priority_updates','sql_log_off',      'sql_log_update',   'sql_select_limit',
'sql_small_result', 'sql_big_result',   'sql_warnings',     'straight_join',
'starting',         'status',           'string',           'table',
'tables',           'temporary',        'terminated',       'text',
'then',             'time',             'timestamp',        'tinyblob',
'tinytext',         'tinyint',          'trailing',         'to',
'type',             'use',              'using',            'unique',
'unlock',           'unsigned',         'update',           'usage',
'values',           'varchar',          'variables',        'varying',
'varbinary',        'with',             'write',            'when',
'where',            'year',             'year_month',       'zerofill'
);if (in_array($word, $disallowed)) {return 2;}
return 0;}
?>