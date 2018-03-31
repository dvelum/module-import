<?php
/**
 *  Copyright (C) 2018  Kirill Yegorov
 *
 *  This program is free software: you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation, either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *  along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */
declare(strict_types=1);

namespace Dvelum\Import\Writer;

class Result
{
    /**
     * @var int $success
     */
    protected $success;
    /**
     * @var int $errors
     */
    protected $errors;
    /**
     * @var int $new
     */
    protected $new;
    /**
     * @var int $update
     */
    protected $update;
    /**
     * @var float $time
     */
    protected $time;

    /**
     * @var array $messages
     */
    protected $messages;

    /**
     * @return int
     */
    public function getSuccess(): int
    {
        return $this->success;
    }

    /**
     * @param int $success
     */
    public function setSuccess(int $success): void
    {
        $this->success = $success;
    }

    /**
     * @return int
     */
    public function getErrors(): int
    {
        return $this->errors;
    }

    /**
     * @param int $errors
     */
    public function setErrors(int $errors): void
    {
        $this->errors = $errors;
    }

    /**
     * @return int
     */
    public function getNew(): int
    {
        return $this->new;
    }

    /**
     * @param int $new
     */
    public function setNew(int $new): void
    {
        $this->new = $new;
    }

    /**
     * @return int
     */
    public function getUpdate(): int
    {
        return $this->update;
    }

    /**
     * @param int $update
     */
    public function setUpdate(int $update): void
    {
        $this->update = $update;
    }

    /**
     * @return float
     */
    public function getTime(): float
    {
        return $this->time;
    }

    /**
     * @param float $time
     */
    public function setTime(float $time): void
    {
        $this->time = $time;
    }

    /**
     * @return array
     */
    public function getMessages(): array
    {
        return $this->messages;
    }

    /**
     * @param array $messages
     */
    public function setMessages(array $messages): void
    {
        $this->messages = $messages;
    }

    public function  __toArray() : array
    {
        return get_object_vars($this);
    }

}