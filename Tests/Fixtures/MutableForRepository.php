<?php
/**
* @author SignpostMarv
*/
declare(strict_types=1);

namespace SignpostMarv\DaftTypedObject\Fixtures;

use SignpostMarv\DaftTypedObject\DaftTypedObjectForRepository;

class MutableForRepository extends Mutable implements DaftTypedObjectForRepository
{
	public function ObtainId() : array
	{
		return [
			'id' => $this->id,
		];
	}
}
