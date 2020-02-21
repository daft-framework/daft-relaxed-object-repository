<?php
/**
* @author SignpostMarv
*/
declare(strict_types=1);

namespace DaftFramework\RelaxedObjectRepository;

use Throwable;

/**
 * @template T1 as object
 * @template T2 as array<string, scalar>
 * @template T3 as array<string, scalar|array|object|null>
 */
interface ObjectRepository
{
	/**
	 * @param T3 $_options
	 */
	public function __construct(array $_options);

	/**
	 * @param T1 $object
	 */
	public function UpdateObject(
		object $object
	) : void;

	/**
	 * @param T2 $id
	 */
	public function ForgetObject(
		array $id
	) : void;

	/**
	 * @param T2 $id
	 */
	public function RemoveObject(
		array $id
	) : void;

	/**
	 * @param T2 $id
	 */
	public function RecallObject(
		array $id,
		Throwable $not_found = null
	) : object;

	/**
	 * @param T2 $id
	 */
	public function MaybeRecallObject(
		array $id
	) : ? object;

	/**
	 * @param T1 $object
	 *
	 * @return T2
	 */
	public function ObtainIdFromObject(object $object) : array;
}
