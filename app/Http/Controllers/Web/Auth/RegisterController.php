<?php

namespace App\Http\Controllers\Web\Auth;

use App\Models\v1\User;
use App\Utilities\v1\Country;
use App\Jobs\SendWelcomeEmail;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/dashboard';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Show the application registration form.
     *
     * @return \Illuminate\Http\Response
     */
    public function showRegistrationForm()
    {
        return view('auth.register')
            ->with('countries', Country::all())
            ->with('months', [
                "01" => "January", "02" => "February", "03" => "March", "04" => "April",
                "05" => "May", "06" => "June", "07" => "July", "08" => "August",
                "09" => "September", "10" => "October", "11" => "November",
                "12" => "December",
            ])
            ->with('days', [
                "01" => "1", "02" => "2", "03" => "3", "04" => "4",
                "05" => "5", "06" => "6", "07" => "7", "08" => "8",
                "09" => "9", "10" => "10", "11" => "11", "12" => "12",
                "13" => "13", "14" => "14", "15" => "15", "16" => "16",
                "17" => "17", "18" => "18", "19" => "19", "20" => "20",
                "21" => "21", "22" => "22", "23" => "23", "24" => "24",
                "25" => "25", "26" => "26", "27" => "27", "28" => "28",
                "29" => "29", "30" => "30", "31" => "31",
            ])
            ->with('years', [
                "1930" => "1930", "1931" => "1931", "1932" => "1932", "1933" => "1933",
                "1934" => "1934", "1935" => "1935", "1936" => "1936", "1937" => "1937",
                "1938" => "1938", "1939" => "1939", "1940" => "1940", "1941" => "1941",
                "1942" => "1942", "1943" => "1943", "1944" => "1944", "1945" => "1945",
                "1946" => "1946", "1947" => "1947", "1948" => "1948", "1949" => "1949",
                "1950" => "1950", "1951" => "1951", "1952" => "1952", "1953" => "1953",
                "1954" => "1954", "1955" => "1955", "1956" => "1956", "1957" => "1957",
                "1958" => "1958", "1959" => "1959", "1960" => "1960", "1961" => "1961",
                "1962" => "1962", "1963" => "1963", "1964" => "1964", "1965" => "1965",
                "1966" => "1966", "1967" => "1967", "1968" => "1968", "1969" => "1969",
                "1970" => "1970", "1971" => "1971", "1972" => "1972", "1973" => "1973",
                "1974" => "1974", "1975" => "1975", "1976" => "1976", "1977" => "1977",
                "1978" => "1978", "1979" => "1979", "1980" => "1980", "1981" => "1981",
                "1982" => "1982", "1983" => "1983", "1984" => "1984", "1985" => "1985",
                "1986" => "1986", "1987" => "1987", "1988" => "1988", "1989" => "1989",
                "1990" => "1990", "1991" => "1991", "1992" => "1992", "1993" => "1993",
                "1994" => "1994", "1995" => "1995", "1996" => "1996", "1997" => "1997",
                "1998" => "1998", "1999" => "1999", "2000" => "2000", "2001" => "2001",
                "2002" => "2002", "2003" => "2003", "2004" => "2004", "2005" => "2005",
                 "2006" => "2006", "2007" => "2007", "2008" => "2008", "2009" => "2009",
                 "2010" => "2010", "2011" => "2011", "2012" => "2012", "2013" => "2013",
                 "2014" => "2014", "2015" => "2015",
            ]);
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        if ( ! isset($data['birthday'])) {
            $data['birthday'] = make_date_string(
                $data['dob_year'], $data['dob_month'], $data['dob_day']
            );
        }

        return Validator::make($data, [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'gender' => 'required|in:male,female',
            'birthday' => 'required|date',
            'country' => 'required|country',
            'password' => 'required|string|min:6|confirmed',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        if ( ! isset($data['birthday'])) {
            $data['birthday'] = make_date_string(
                $data['dob_year'], $data['dob_month'], $data['dob_day']
            );
        }

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
            'gender' => $data['gender'],
            'birthday' => $data['birthday'],
            'country' => $data['country'],
            'timezone' => $data['timezone']
        ]);

        $user->slug()->create([
            'url' => generate_slug($data['name'])
        ]);

        dispatch(new SendWelcomeEmail($user));

        return $user;
    }
}