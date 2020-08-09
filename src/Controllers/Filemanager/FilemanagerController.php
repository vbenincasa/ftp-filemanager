<?php

namespace FTPApp\Controllers\Filemanager;

use FTPApp\Controllers\FilemanagerControllerAbstract;
use FTPApp\Http\JsonResponse;

class FilemanagerController extends FilemanagerControllerAbstract
{
    public function index()
    {
        /**
         * Check if the session cookie exists, if doesn't
         * make a redirection to login page.
         */
        if (!$this->session()->cookieExists()) {
            return $this->redirectToRoute('login');
        }

        /**
         * If a session already exists regenerate the session ID
         * and delete the old session associated file.
         */
        $this->session()->regenerateID(true);

        return $this->renderWithResponse('filemanager', ['homeUrl' => $this->generateUrl('home')]);
    }

    public function browse()
    {
        return new JsonResponse([
            'result' => $this->ftpAdapter()->browse($this->getParameters()['path']),
        ]);
    }

    public function addFile()
    {
        $params = $this->request->getJSONBodyParameters();
        return new JsonResponse([
            'result' => $this->ftpAdapter()->addFile($params['path'] . $params['name'])
        ], 201);
    }

    public function addFolder()
    {
        $params = $this->request->getJSONBodyParameters();
        $folder = ltrim($params['path'] . $params['name'], '/');
        return new JsonResponse([
            'result' => $this->ftpAdapter()->addFolder($folder)
        ], 201);
    }

    public function getFileContent()
    {
        return new JsonResponse([
            'result' => $this->ftpAdapter()->getFileContent($this->request->getParameters()['file'])
        ]);
    }

    public function updateFileContent()
    {
        return new JsonResponse([
            'result' => $this->ftpAdapter()->updateFileContent(
                $this->request->getJSONBodyParameters()['file'],
                $this->request->getJSONBodyParameters()['content']
            )
        ]);
    }

    public function remove()
    {
        return new JsonResponse([
            'result' => $this->ftpAdapter()->remove($this->request->getJSONBodyParameters()['files'])
        ]);
    }
}
