<?php
/**
* @author SignpostMarv
*/
declare(strict_types=1);

namespace SignpostMarv\DaftTypedObject;

use Throwable;

/**
* @template T1 as DaftTypedObjectForRepository
* @template T2 as array<string, scalar>
*/
interface DaftTypedObjectRepository
{
	/**
	* @param T1 $object
	*/
	public function UpdateTypedObject(
		DaftTypedObjectForRepository $object
	) : void;

	/**
	* @param T2 $id
	*/
	public function ForgetTypedObject(
		array $id
	) : void;

	/**
	* @param T2 $id
	*/
	public function RemoveTypedObject(
		array $id
	) : void;

	/**
	* @param T2 $id
	*/
	public function RecallTypedObject(
		array $id,
		Throwable $not_found = null
	) : DaftTypedObjectForRepository;

	/**
	* @param T2 $id
	*/
	public function MaybeRecallTypedObject(
		array $id
	) : ? DaftTypedObjectForRepository;

	/**
	* @param class-string<T1> $type
	* @param mixed ...$args
	*
	* @return static<T1, T2>
	*/
	public static function DaftTypedObjectRepositoryByType(
		string $type,
		...$args
	) : DaftTypedObjectRepository;
}
