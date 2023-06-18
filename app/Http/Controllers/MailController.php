<?php

namespace App\Http\Controllers;

use App\Services\ImagesService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\ValidationException;
use Throwable;

class MailController extends Controller
{
    function sendForm(Request $request) {
        try {
            $validatedData = $request->validate([
                'name' => 'required|string',
                'email' => 'required|email',
                'text' => 'nullable|string',
                'file' => 'nullable|max:12000'
            ]);

            if ($request->hasFile('file')) {
                $file = $request->file('file');
            } else {
                $file = null;
            }

            Mail::send(['text' => view('mail')->with(['text' => $validatedData['text'], 'name' => $validatedData['name'], 'email' => $validatedData['email']])], ['name', '4 Цвета'], function ($message) use ($file) {
                $message->to('d.martyshev@yandex.ru', 'to dima')->subject('Заявка с сайта 4 Цвета');
                if ($file) {
                    $message->attach($file->getRealPath(), array(
                        'as' => $file->getClientOriginalName(), 'mime' => $file->getMimeType()));
                }
                $message->from('4zveta.mailer@mail.ru', '4 Цвета');
            });

            return response(['message' => 'The mail was sent'], 200);
        } catch (ValidationException $e) {
            return $this->returnBadResponse(400, $e->errors());
        } catch (Throwable $e) {
            return $this->returnBadResponse(500, $e->getMessage());
        }
    }
}
