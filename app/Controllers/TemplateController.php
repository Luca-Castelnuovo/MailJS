<?php

namespace App\Controllers;

use Exception;
use CQ\DB\DB;
use CQ\Config\Config;
use CQ\Helpers\JWT;
use CQ\Helpers\UUID;
use CQ\Helpers\Session;
use CQ\Helpers\Variant;
use CQ\Controllers\Controller;
use App\Validators\TemplateValidator;


class TemplateController extends Controller
{
    /**
     * Check if user owns template
     *
     * @param int $template_id
     * @param string $user_id
     * 
     * @return boolean
     */
    protected function hasUserTemplate($template_id, $user_id)
    {
        return DB::has('templates', [
            'id' => $template_id,
            'user_id' => $user_id
        ]);
    }

    /**
     * Create template
     *
     * @param object $request
     *
     * @return Json
     */
    public function create($request)
    {
        try {
            TemplateValidator::create($request->data);
        } catch (Exception $e) {
            return $this->respondJson(
                'Invalid domain',
                json_decode($e->getMessage()),
                422
            );
        }

        $variant_provider = new Variant([
            'user' => Session::get('variant'),
            'type' => 'max_templates',
            'current_value' => DB::count('templates', ['user_id' => Session::get('id')])
        ]);
        if (!$variant_provider->limitReached()) {
            return $this->respondJson(
                "Template quota reached, max {$variant_provider->configuredValue()}",
                [],
                400
            );
        }

        DB::create(
            'templates',
            [
                'id' => UUID::v6(),
                'user_id' => Session::get('id'),
                'key_id' => UUID::v4(),
                'name' => $request->data->name,
                'captcha_key' => $request->data->captcha_key,
                'email_to' => $request->data->email_to,
                'email_replyTo' => $request->data->email_replyTo,
                'email_cc' => $request->data->email_cc,
                'email_bcc' => $request->data->email_bcc,
                'email_fromName' => $request->data->email_fromName,
                'email_subject' => $request->data->email_subject,
                'email_content' => $request->data->email_content
            ]
        );

        return $this->respondJson(
            'Template Created',
            ['reload' => true]
        );
    }

    /**
     * Update template
     *
     * @param object $request
     * @param string $id
     *
     * @return Json
     */
    public function update($request, $id)
    {
        if (!$this->hasUserTemplate($id, Session::get('id'))) {
            return $this->respondJson(
                'Template not owned',
                [],
                403
            );
        }

        try {
            TemplateValidator::update($request->data);
        } catch (Exception $e) {
            return $this->respondJson(
                'Invalid Input',
                json_decode($e->getMessage()),
                422
            );
        }

        $template = DB::get(
            'templates',
            [
                'name',
                'captcha_key',
                'email_to',
                'email_replyTo',
                'email_cc',
                'email_bcc',
                'email_fromName',
                'email_subject',
                'email_content'
            ],
            [
                'id' => $id
            ]
        );

        DB::update(
            'templates',
            [
                'name' => $request->data->name ?: $template['name'],
                'captcha_key' => $request->data->captcha_key ?: $template['captcha_key'],
                'email_to' => $request->data->email_to ?: $template['email_to'],
                'email_replyTo' => $request->data->email_replyTo ?: $template['email_replyTo'],
                'email_cc' => $request->data->email_cc ?: $template['email_cc'],
                'email_bcc' => $request->data->email_bcc ?: $template['email_bcc'],
                'email_fromName' => $request->data->email_fromName ?: $template['email_fromName'],
                'email_subject' => $request->data->email_subject ?: $template['email_subject'],
                'email_content' => $request->data->email_content ?: $template['email_content']
            ],
            [
                'id' => $id
            ]
        );

        return $this->respondJson(
            'Template Updated',
            ['reload' => true]
        );
    }

    /**
     * Delete template
     *
     * @param string $id
     *
     * @return Json
     */
    public function delete($id)
    {
        if (!$this->hasUserTemplate($id, Session::get('id'))) {
            return $this->respondJson(
                'Template not owned',
                [],
                403
            );
        }

        DB::delete('templates', [
            'id' => $id,
        ]);

        DB::delete('history', [
            'template_id' => $id,
        ]);

        return $this->respondJson(
            'Template Deletd',
            ['reload' => true]
        );
    }

    /**
     * Create access_key
     *
     * @param object $request
     * @param string $id
     *
     * @return Json
     */
    public function createKey($request, $id)
    {
        if (!$this->hasUserTemplate($id, Session::get('id'))) {
            return $this->respondJson(
                'Template not owned',
                [],
                403
            );
        }

        try {
            TemplateValidator::createKey($request->data);
        } catch (Exception $e) {
            return $this->respondJson(
                'Invalid Input',
                json_decode($e->getMessage()),
                422
            );
        }

        $template = DB::get('templates', ['key_id'], ['id' => $id]);
        if (!$template) {
            return $this->respondJson(
                'Template not found',
                [],
                404
            );
        }

        $key = JWT::create([
            'type' => 'submission',
            'sub' => $template['key_id'],
            'allowed_origin' => $request->data->allowed_origin
        ], Config::get('jwt.submission'));

        return $this->respondJson('Key Created', [
            'prompt' => [
                'title' => 'Your key is:',
                'data' => $key
            ]
        ]);
    }

    /**
     * Reset all access_key
     *
     * @param string $id
     *
     * @return Json
     */
    public function resetKey($id)
    {
        if (!$this->hasUserTemplate($id, Session::get('id'))) {
            return $this->respondJson(
                'Template not owned',
                [],
                403
            );
        }

        DB::update('templates', ['key_id' => UUID::v4()], ['id' => $id]);

        return $this->respondJson(
            'Key Reset',
            ['reload' => true]
        );
    }
}
