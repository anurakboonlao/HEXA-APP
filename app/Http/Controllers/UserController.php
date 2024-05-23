<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use DB;
use App\User;
use Auth;

class UserController extends Controller
{
    protected $request;
    protected $user;

    public function __construct(
        Request $request,
        User $user
    ) {
        $this->request = $request;
        $this->user = $user;
    }

    public function adminLogin()
    {
        if (Auth::check()) {

            return redirect('admin/user/profile');
        }

        $message = [
            'status' => false,
            'message' => 'ไม่พบข้อมูลผู้เข้าใช้นี้'
        ];

        if ($this->request->isMethod('post')) {

            try {
    
                $email = $this->request->input('email');
                $password = $this->request->input('password');
                
                if (Auth::attempt(['email' => $email, 'password' => $password], true)) {

                    $redirectUrl = "admin/dashboard";
                    
                    return redirect($redirectUrl);
                }

            } catch (\Exception $e) {
            
                $message = flash_message($e->getMessage());           
            }

            return redirect('admin')->with('message', $message);
        }

        return view('admin.login');
    }

    public function index()
    {
        $response = [];
        $users = $this->user
            ->where([
                ['role', '!=', 1]
            ])
            ->orderBy('id', 'desc')
            ->get();
        
        $response['users'] = $users;
    
        return view('admin.user.index', $response);
    }

    public function create()
    {
        $response = [
            'message' => [
                'status' => false,
                'message' => "Errors."
            ]
        ];

        if ($this->request->isMethod('post')) {
            
            $this->request->validate([
                'email' => 'required|email|unique:users',
                'name' => 'required',
                'password' => 'required',
            ]);

            $params = $this->request->all();
            $password = $params['password'];
            $this->user->email = $params['email'] ?? null;
            $this->user->name = $params['name'] ?? null;
            $this->user->password = bcrypt($password);
            $this->user->role = $params['role'];
            $this->user->image = $params['image'];
            
            if ($this->user->save()) {
                $response['message'] = [
                    'status' => true,
                    'message' => "Success."
                ];
            }

            return redirect()->back()->with('message', $response['message']);
        }

        return view('admin.user.create', $response);
    }

    public function update($id)
    {
        $response = [
            'message' => [
                'status' => false,
                'message' => "Errors."
            ]
        ];

        $user = $this->user->find($id);
        $response['user'] = $user;

        if ($this->request->isMethod('post')) {
            $this->request->validate([
                'name' => 'required'
            ]);

            $params = $this->request->all();
            $password = $params['password'] ?? null;
            $user->name = $params['name'] ?? null;
            
            if ($password) {
                $user->password = bcrypt($password);
            }

            $user->role = $params['role'];
            $user->image = $params['image'];
            
            if ($user->save()) {
                $response['message'] = [
                    'status' => true,
                    'message' => "Success."
                ];
            }

            return redirect()->back()->with('message', $response['message']);
        }

        return view('admin.user.update', $response);
    }

    public function resetPassword()
    {
        $response = [
            'message' => [
                'status' => false,
                'message' => "Errors."
            ]
        ];
        
        if ($this->request->isMethod('post')) {
            
            try {

                $user = $this->user->where('email', $this->request->input('email'))->first();
                if (!$user) {
                    $response['message']['message'] = 'Unable to find mail account, The user account was not found.';
                    return redirect()->back()->with('message', $response['message']);
                }
                
                $password = date('ydmHis');
                $user->password = bcrypt($password);
                if ($user->save()) {
                    
                    $this->sendResetPasswordEmail($user, $password);
                    $response['message'] = [
                        'status' => true,
                        'message' => "Your password has been reset successfully!, new password sent to email."
                    ];
                }

            } catch (\Exception $e) {
            
                $response['message'] = flash_message($e->getMessage());            
            }

            return redirect()->back()->with('message', $response['message']);
        }
        
        return view('admin.reset_password');
    }
    
    public function profile()
    {
        $response = [
            'message' => [
                'status' => false,
                'message' => "Errors."
            ]
        ];

        $id = auth()->user()->id;
        $user = $this->user->find($id);
        $response['user'] = $user;

        if ($this->request->isMethod('post')) {
            $params = $this->request->all();
            if ($this->request->hasFile('image')) {
                $upload = $this->uploadImage($this->request->file('image'), 'users');
                if (!$upload['status']) {
                    $response['message']['message'] = $upload['file_name'];
                    return redirect()->back()->with('message', $response['message']);
                }

                $user->image = $upload['file_name'];
            }

            $user->name = $params['name'] ?? null;

            if ($user->save()) {
                $response['message'] = [
                    'status' => true,
                    'message' => "Success."
                ];
            }

            return redirect()->back()->with('message', $response['message']);
        }

        return view('admin.user.profile', $response);
    }

    public function changePassword()
    {
        $response = [
            'message' => [
                'status' => false,
                'message' => "Errors."
            ]
        ];

        $id = auth()->user()->id;
        $user = $this->user->find($id);
        $response['user'] = $user;

        if ($this->request->isMethod('post')) {
            $this->request->validate(
            [
                'old_password' => 'required',
                'password' => 'required|different:old_password|confirmed|min:8',
                'password_confirmation' => 'required'
            ],
            [
                'password.regex' => 'Password must contain at least 1 lower-case and capital letter, a number and symbol.'
            ]);

            $params = $this->request->all();
            $user->password = bcrypt($params['password']);
            if ($user->save()) {
                $response['message'] = [
                    'status' => true,
                    'message' => "Your password changed please logout and login again."
                ];
            }

            return redirect()->back()->with('message', $response['message']);
        }

        return view('admin.user.change_password', $response);
    }

    public function delete($userId)
    {
        $response = [
            'message' => [
                'status' => false,
                'message' => "Errors."
            ]
        ];

        if ($this->user->destroy($id)) {
            $response['message'] = [
                'status' => true,
                'message' => "Success."
            ];
        }

        return redirect()->back()->with('message', $response['message']);
    }
}