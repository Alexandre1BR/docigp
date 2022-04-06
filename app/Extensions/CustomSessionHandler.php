<?php

namespace App\Extensions;

use App\Events\SessionExpired;
use Illuminate\Session\FileSessionHandler;
use Symfony\Component\Finder\Finder;

class CustomSessionHandler extends FileSessionHandler
{
    /**
     * {@inheritdoc}
     */
    public function forceRead($sessionId)
    {
        if ($this->files->isFile($path = $this->path.'/'.$sessionId)) {
            return $this->files->sharedGet($path);
        }

        return '';
    }

    public function getDataFromSession($sessionId)
    {
        return @unserialize($this->forceRead($sessionId));
    }

    public function getLoggedUserFromSession($sessionId)
    {
        return $this->getDataFromSession($sessionId)[auth()->guard()->getName()] ?? null;
    }

    public function getTokenSession($sessionId)
    {
        return $this->getDataFromSession($sessionId)['_token'] ?? null;
    }

    /**
     * {@inheritdoc}
     */
    public function gc($lifetime)
    {
        $files = Finder::create()
            ->in($this->path)
            ->files()
            ->ignoreDotFiles(true)
            ->date('<= now - '.$lifetime.' seconds');

        foreach ($files as $file) {
            if($token = $this->getTokenSession($file->getFilenameWithoutExtension())){
                event(new SessionExpired($token));
            }

            $this->files->delete($file->getRealPath());
        }
    }
}
