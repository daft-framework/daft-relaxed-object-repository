<?php
/**
* @author SignpostMarv
*/
declare(strict_types=1);

namespace DaftFramework\RelaxedObjectRepository;

/**
 * @template OBJECT as object
 * @template SIMPLE as array<string, scalar|array|object|null>
 * @template ID as array<string, scalar>
 * @template CTORARGS as array<string, scalar|array|object|null>
 *
 * @template-extends ObjectRepository<OBJECT, ID, CTORARGS>
 */
interface ConvertingRepository extends ObjectRepository
{
	/**
	 * @param SIMPLE $array
	 *
	 * @return OBJECT
	 */
	public function ConvertSimpleArrayToObject(array $array) : object;

	/**
	 * @param OBJECT $object
	 *
	 * @return SIMPLE
	 */
	public function ConvertObjectToSimpleArray(object $object) : array;
}
