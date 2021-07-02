<?php
/**
* @author SignpostMarv
*/
declare(strict_types=1);

namespace DaftFramework\RelaxedObjectRepository;

/**
 * @template TYPE as object
 * @template SIMPLE as array<string, scalar|array|object|null>
 * @template ID as array<string, scalar>
 * @template CTORARGS as array<string, scalar|array|object|null>
 *
 * @template-extends ObjectRepository<TYPE, ID, CTORARGS>
 */
interface ConvertingRepository extends ObjectRepository
{
	/**
	 * @param SIMPLE $array
	 *
	 * @return TYPE
	 */
	public function ConvertSimpleArrayToObject(array $array) : object;

	/**
	 * @param TYPE $object
	 *
	 * @return SIMPLE
	 */
	public function ConvertObjectToSimpleArray(object $object) : array;
}
