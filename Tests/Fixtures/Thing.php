<?php
/**
* @author SignpostMarv
*/
declare(strict_types=1);

namespace DaftFramework\RelaxedObjectRepository\Fixtures;

use InvalidArgumentException;
use function is_string;

class Thing
{
	public int $id;

	public string $name;

	/**
	 * @param int|string $id
	 */
	public function __construct($id, string $name)
	{
		if (is_string($id) && ! ctype_digit($id)) {
			throw new InvalidArgumentException('Argument 1 must be a digit!');
		}

		$this->id = (int) $id;
		$this->name = $name;
	}
}
