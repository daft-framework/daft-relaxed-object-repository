<?php
/**
* @author SignpostMarv
*/
declare(strict_types=1);

namespace SignpostMarv\DaftTypedObject\Fixtures;

use SignpostMarv\DaftTypedObject\DaftTypedObjectForRepository;

class ImmutableForRepository extends Immutable implements DaftTypedObjectForRepository
{
	public function ObtainId() : array
	{
		return [
			'id' => $this->id,
		];
	}
}
