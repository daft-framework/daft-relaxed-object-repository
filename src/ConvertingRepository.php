<?php
/**
* @author SignpostMarv
*/
declare(strict_types=1);

namespace DaftFramework\RelaxedObjectRepository;

/**
 * @template T1 as object
 * @template T2 as array<string, scalar|null>
 * @template T3 as array<string, scalar>
 *
 * @template-extends ObjectRepository<T1, T3>
 */
interface ConvertingRepository extends ObjectRepository
{
	/**
	 * @param T2 $array
	 *
	 * @return T1
	 */
	public function ConvertSimpleArrayToObject(array $array) : object;

	/**
	 * @param T1 $object
	 *
	 * @return T2
	 */
	public function ConvertObjectToSimpleArray(object $object) : array;
}
