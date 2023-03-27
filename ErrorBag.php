<?php

namespace OH;

class ErrorBag
{
    private array $errors = [];

    public function addError(string $error): void
    {
        $this->errors[] = $error;
    }

    public function getErrors(): array
    {
        return $this->errors;
    }

    public function hasErrors(): bool
    {
        return !empty($this->errors);
    }

    public function clearErrors(): void
    {
        $this->errors = [];
    }

    public function __toString(): string
    {
        return implode(', ', $this->errors);
    }


}