<?php
/**
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; under version 2
 * of the License (non-upgradable).
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
 * Copyright (c) 2018 (original work) Open Assessment Technologies SA;
 *
 *
 */

/**
 * Generated using taoDevTools 3.10.0
 */
return array(
    'name' => 'taoOffline',
    'label' => 'Tao Offline',
    'description' => 'An extension to assist the setup of an offline context. Setup synchronisation and encryption by test center identifier',
    'license' => 'GPL-2.0',
    'version' => '2.4.0',
    'author' => 'Open Assessment Technologies SA',
    'requires' => array(
        'taoTestCenter' => '>=8.1.0',
        'taoSync' => '>=4.2.0'
    ),
    'update' => oat\taoOffline\scripts\update\Updater::class,
    'managementRole' => 'http://www.tao.lu/Ontologies/generis.rdf#taoOfflineManager',
    'routes' => array(
        '/taoOffline/api' => ['class' => \oat\taoOffline\model\routing\ApiRoute::class],
    ),
    'acl' => array(
        array('grant', 'http://www.tao.lu/Ontologies/generis.rdf#taoOfflineManager', array('ext'=>'taoOffline')),
    ),
);