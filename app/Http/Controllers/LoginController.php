<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PDO;
use Exception;

class LoginController extends Controller
{
    public function index()
    {
        return view('login');
    }

    public function login(Request $request){
        $request->validate([
            'server_name' => 'required',
            'username' => 'required',
            'password' => 'required',
        ]);

        try {
            // Create a connection to the SQL Server database
            $DataSourceName = "sqlsrv:server={$request->server_name};Database=master";

            // Attempt to connect using the provided credentials
            $connect = new PDO($DataSourceName, $request->username, $request->password);

            // If the connection is successful, store the credentials in the session
            session([
                'sql_server' => $request->server_name,
                'sql_username' => $request->username,
                'sql_password' => $request->password,
            ]);

            // Redirect to the dashboard with a success message
            return redirect()->route('dashboard')->with('success', 'Login successful!');

        } catch (Exception $e) {

        // If the connection fails, redirect back with an error message
        return back()->with('error', 'Login failed: ' . $e->getMessage());

        }
    }

    public function dashboard()
    {
        if(!session()->has('sql_server')){
            return redirect()->route('login');
        }
        return view('dashboard');
    }
}
