<?php
/**
* @author SignpostMarv
*/
declare(strict_types=1);

namespace DaftFramework\RelaxedObjectRepository;

/**
 * @template T1 as object
 * @template T2 as array<string, scalar>
 * @template T3 as array<string, scalar|null>
 *
 * @template-extends ObjectRepository<T1, T2>
 */
interface AppendableObjectRepository extends ObjectRepository
{
	/**
	 * @param T1 $object
	 *
	 * @return T1
	 */
	public function AppendObject(
		object $object
	) : object;

	/**
	 * @param T3 $data
	 *
	 * @return T1
	 */
	public function AppendObjectFromArray(
		array $data
	) : object;
}
