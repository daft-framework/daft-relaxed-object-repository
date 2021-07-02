<?php
/**
* @author SignpostMarv
*/
declare(strict_types=1);

namespace DaftFramework\RelaxedObjectRepository;

/**
 * @template TYPE as object
 * @template ID as array<string, scalar>
 * @template PARTIAL as array<string, scalar|array|object|null>
 * @template CTORARGS as array<string, scalar|array|object|null>
 *
 * @template-extends ObjectRepository<TYPE, ID, CTORARGS>
 */
interface AppendableObjectRepository extends ObjectRepository
{
	/**
	 * @param TYPE $object
	 *
	 * @return TYPE
	 */
	public function AppendObject(
		object $object
	) : object;

	/**
	 * @param PARTIAL $data
	 *
	 * @return TYPE
	 */
	public function AppendObjectFromArray(
		array $data
	) : object;
}
