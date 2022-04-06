<?php

namespace App\Extensions;

use App\Events\SessionExpired;
use Illuminate\Session\FileSessionHandler;
use Illuminate\Support\Carbon;
use Symfony\Component\Finder\Finder;

class CustomSessionHandler extends FileSessionHandler
{
    /**
     * {@inheritdoc}
     */
    public function destroy($sessionId)
    {
        $this->eventIfloggedSessionExpired($sessionId);
        $this->files->delete($this->path.'/'.$sessionId);
        return true;
    }

    public function eventIfloggedSessionExpired($sessionId)
    {
        if (($userId = $this->getLoggedUserFromSession($sessionId)) && ($token = $this->getTokenSession($sessionId))) {
            event(new SessionExpired($token));
        }

        return [
            'userId' => $userId ?? null,
            'token' => $token ?? null
        ];
    }

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
            $this->eventIfloggedSessionExpired($file->getFilenameWithoutExtension());
            $this->files->delete($file->getRealPath());
        }
    }
}
