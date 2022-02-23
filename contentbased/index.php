<?php

/**
 * PHP item based filtering
 *
 * This library is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 * Lesser General Public License for more details.
 *
 * @package   PHP item based filtering
 */

require_once 'recommend.php';
require_once 'content_based.php';

$objects = [
	'The Matrix' => ['Action', 'Sci-Fi'],
	'Lord of The Rings' => ['Adventure', 'Drama', 'Fantasy'],
	'Batman' => ['Action', 'Drama', 'Crime'],
	'Fight Club' => ['Drama'],
	'Pulp Fiction' => ['Drama', 'Crime'],
	'Django' => ['Drama', 'Western'],
];

$user = ['Drama', 'Crime'];

$engine = new ContentBasedRecommend($user, $objects);

$engine->getRecommendation();
var_dump($engine);