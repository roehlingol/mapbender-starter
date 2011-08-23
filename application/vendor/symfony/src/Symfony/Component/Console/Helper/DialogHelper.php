<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Component\Console\Helper;

use Symfony\Component\Console\Output\OutputInterface;

/**
 * The Dialog class provides helpers to interact with the user.
 *
 * @author Fabien Potencier <fabien@symfony.com>
 */
class DialogHelper extends Helper
{
    private $inputStream = STDIN;

    /**
     * Asks a question to the user.
     *
     * @param OutputInterface $output
     * @param string|array    $question The question to ask
     * @param string          $default  The default answer if none is given by the user
     *
     * @return string The user answer
     */
    public function ask(OutputInterface $output, $question, $default = null)
    {
        $output->write($question);

        $ret = fgets($this->inputStream, 4096);
        if (false === $ret) {
            throw new \RuntimeException('Aborted');
        }
        $ret = trim($ret);

        return strlen($ret) > 0 ? $ret : $default;
    }

    /**
     * Asks a confirmation to the user.
     *
     * The question will be asked until the user answer by nothing, yes, or no.
     *
     * @param OutputInterface $output
     * @param string|array    $question The question to ask
     * @param Boolean         $default  The default answer if the user enters nothing
     *
     * @return Boolean true if the user has confirmed, false otherwise
     */
    public function askConfirmation(OutputInterface $output, $question, $default = true)
    {
        $answer = 'z';
        while ($answer && !in_array(strtolower($answer[0]), array('y', 'n'))) {
            $answer = $this->ask($output, $question);
        }

        if (false === $default) {
            return $answer && 'y' == strtolower($answer[0]);
        }

        return !$answer || 'y' == strtolower($answer[0]);
    }

    /**
     * Asks for a value and validates the response.
     *
     * The validator receives the data to validate. It must return the
     * validated data when the data is valid and throw an exception
     * otherwise.
     *
     * @param OutputInterface $output
     * @param string|array    $question
     * @param callback        $validator A PHP callback
     * @param integer         $attempts Max number of times to ask before giving up (false by default, which means infinite)
     * @param string          $default  The default answer if none is given by the user
     *
     * @return mixed
     *
     * @throws \Exception When any of the validator returns an error
     */
    public function askAndValidate(OutputInterface $output, $question, $validator, $attempts = false, $default = null)
    {
        $error = null;
        while (false === $attempts || $attempts--) {
            if (null !== $error) {
                $output->writeln($this->getHelperSet()->get('formatter')->formatBlock($error->getMessage(), 'error'));
            }

            $value = $this->ask($output, $question, $default);

            try {
                return call_user_func($validator, $value);
            } catch (\Exception $error) {
            }
        }

        throw $error;
    }

    /**
     * Sets the input stream to read from when interacting with the user.
     *
     * This is mainly useful for testing purpose.
     *
     * @param resource $stream The input stream
     */
    public function setInputStream($stream)
    {
        $this->inputStream = $stream;
    }

    /**
     * Returns the helper's canonical name
     */
    public function getName()
    {
        return 'dialog';
    }
}