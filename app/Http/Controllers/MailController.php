<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Jobs\SendEmail;
use App\Models\MailSent;
use App\Models\Attachment;
use Validator;
use Log;

class MailController extends Controller
{
    public function emailSend(request $request)
    {
        $data = $request->all();
        foreach ($data['data'] as $content) {
            $validator = validator::make($content, [
                'to' => 'required|email',
                'subject' => 'required',
                'body' => 'required'
            ]);
    
            if ($validator->fails()) {
                Log::error($validator->errors());
                continue;
            }

            if (!empty($content['attachments'])) {
                $fail = false;
                foreach ($content['attachments'] as $attach) {
                    if (empty($attach['file']) || empty($attach['name'])) {
                        Log::error('Missing attachment data');
                        $fail = true;
                        break;
                    }
                };

                if ($fail) {
                    continue ;
                }
            }

            SendEmail::dispatch($content);
        }

        Log::info('Email Dispatched');
        
        return response()->json([
            'success' => true,
            'message' => 'Email sent'
        ], Response::HTTP_OK);
    }

    public function list(request $request) {
        $mails = MailSent::all();

        return view('sentlist', [
            'mails' => MailSent::all()
        ]);
    }

    public function download($id, request $request) {
        $attachment = Attachment::where('id', $id)->first();
        $path = public_path($attachment->name);
        $contents = base64_decode(explode(',', $attachment->content)[1]);
        
        //store file temporarily
        file_put_contents($path, $contents);
        
        //download file and delete it
        return response()->download($path)->deleteFileAfterSend(true);
    }
}
