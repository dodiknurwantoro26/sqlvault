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
    if (!session()->has('sql_server')) {
        return redirect()->route('login');
    }

    try {

        $dsn = "sqlsrv:server=" . session('sql_server') . ";Database=master";

        $connect = new PDO(
            $dsn,
            session('sql_username'),
            session('sql_password')
        );

        $connect->setAttribute(
            PDO::ATTR_ERRMODE,
            PDO::ERRMODE_EXCEPTION
        );

        $sql = "
            SELECT name
            FROM sys.databases
            WHERE name NOT IN ('tempdb')
            ORDER BY name
        ";

        $stmt = $connect->query($sql);

        $databases = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return view('dashboard', compact('databases'));

    } catch (Exception $e) {

        session()->forget([
            'sql_server',
            'sql_username',
            'sql_password',
        ]);

        return redirect()
            ->route('login')
            ->with('error', $e->getMessage());
    }
}
}
