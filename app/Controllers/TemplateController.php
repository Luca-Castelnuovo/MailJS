<?php

namespace App\Controllers;

use DB;
use Zend\Diactoros\ServerRequest;

class TemplateController extends Controller
{
    /**
     * Create template
     * 
     * @param ServerRequest $request
     * 
     * @return JsonResponse
     */
    public function create(ServerRequest $request)
    {
        // TODO: validate parameters

        DB::create(
            'templates',
            [
                'user_id' => $_SESSION['user_id'],
                'name' => $this->get($request->data, 'name'),
                'captcha_key' => $this->get($request->data, 'captcha_key'),
                'email_to' => $this->get($request->data, 'email_to'),
                'email_replyTo' => $this->get($request->data, 'email_replyTo'),
                'email_cc' => $this->get($request->data, 'email_cc'),
                'email_bcc' => $this->get($request->data, 'email_bcc'),
                'email_fromName' => $this->get($request->data, 'email_fromName'),
                'email_subject' => $this->get($request->data, 'email_subject')
            ]
        );

        return $this->respondJson();
    }

    /**
     * Update template
     *
     * @param ServerRequest $request
     * @param int $id
     * 
     * @return JsonResponse
     */
    public function update(ServerRequest $request, $id)
    {
        if (!$this->hasUserTemplate($id, $_SESSION['user_id'])) {
            return $this->respondJsonError(
                'template_not_owned',
                'The user doesn\'t own the template',
                403
            );
        }

        // TODO: validate parameters

        $template = DB::get('templates', '*', $id);

        DB::update(
            'templates',
            [
                'name' => $this->get($request->data, 'name', $template['name']),
                'captcha_key' => $this->get($request->data, 'captcha_key', $template['captcha_key']),
                'email_to' => $this->get($request->data, 'email_to', $template['email_to']),
                'email_replyTo' => $this->get($request->data, 'email_replyTo', $template['email_replyTo']),
                'email_cc' => $this->get($request->data, 'email_cc', $template['email_cc']),
                'email_bcc' => $this->get($request->data, 'email_bcc', $template['email_bcc']),
                'email_fromName' => $this->get($request->data, 'email_fromName', $template['email_fromName']),
                'email_subject' => $this->get($request->data, 'email_subject', $template['email_subject']),
                // 'email_content' => $this->get($request->data, 'email_content', $template['email_content']),
                'updated_at' => date("Y-m-d H:i:s")
            ],
            $id
        );

        return $this->respondJson(DB::get('templates', '*', $id));
    }

    /**
     * Delete template
     * 
     * @param int $id
     *
     * @return JsonResponse
     */
    public function delete($id)
    {
        if (!$this->hasUserTemplate($id, $_SESSION['user_id'])) {
            return $this->respondJsonError(
                'template_not_owned',
                'The user doesn\'t own the template',
                403
            );
        }

        DB::delete('templates', [
            'id' => $id
        ]);

        return $this->respondJson();
    }
}
