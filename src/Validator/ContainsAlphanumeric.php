<?php

namespace App\Validator;

use Symfony\Component\Validator\Attribute\HasNamedArguments;
use Symfony\Component\Validator\Constraint;

#[\Attribute]
class ContainsAlphanumeric extends Constraint
{
    public $message = 'The username "{{ string }}" contains an illegal character: it can only contain letters, numbers or _';
    public string $mode;

    #[HasNamedArguments]
    public function __construct(string $mode, array $groups = null, mixed $payload = null)
    {
        parent::__construct([], $groups, $payload);
        $this->mode = $mode;
    }
}
