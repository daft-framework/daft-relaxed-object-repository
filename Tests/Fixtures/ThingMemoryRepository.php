<?php
/**
* @author SignpostMarv
*/
declare(strict_types=1);

namespace DaftFramework\RelaxedObjectRepository\Fixtures;

/**
 * @template T1 as Thing
 * @template T2 as array{id:int}
 * @template S1 as array{name:string}
 * @template S2 as array{id:int, name:string}
 * @template T3 as array<string, scalar|array|object|null>
 *
 * @template-extends MemoryRepository<T1, T2, S1, S2, T3>
 */
class ThingMemoryRepository extends MemoryRepository
{
	/**
	 * @param T3 $options
	 */
	public function __construct(array $options)
	{
		parent::__construct($options);
	}

	/**
	 * @param T1 $object
	 *
	 * @return T1
	 */
	public function AppendObject(
		object $object
	) : object {
		/** @var S1 */
		$data = [
			'name' => $object->name,
		];

		/**
		 * @var T1
		 */
		return $this->AppendObjectFromArray($data);
	}

	/**
	 * @param S2 $array
	 *
	 * @return T1
	 */
	public function ConvertSimpleArrayToObject(array $array) : object
	{
		/** @var T1 */
		return new Thing($array['id'], $array['name']);
	}

	public function ConvertObjectToSimpleArray(object $object) : array
	{
		/** @var S2 */
		return [
			'id' => $object->id,
			'name' => $object->name,
		];
	}
}
