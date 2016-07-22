<template xmlns:v-validate="http://www.w3.org/1999/xhtml">
    <validator name="UpdateGroup">
        <form id="UpdateGroupForm" class="form-horizontal" novalidate>
            <div class="form-group" :class="{ 'has-error': checkForError('name') }">
                <label for="name" class="col-sm-2 control-label">Name</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" name="name" id="name" v-model="name"
                           placeholder="Group Name" v-validate:name="{ required: true, minlength:1, maxlength:100 }"
                           maxlength="100" minlength="1" required>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 control-label" for="description">Description</label>
                <div class="col-sm-10">
                    <textarea class="form-control" v-model="description" id="description" placeholder="Description of Group"></textarea>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 control-label" for="infoAddress">Address 1</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" v-model="address_one" id="infoAddress" placeholder="Street Address 1">
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 control-label" for="infoAddress2">Address 2</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" v-model="address_two" id="infoAddress2" placeholder="Street Address 2">
                </div>
            </div>

            <div class="row col-sm-offset-2">
                <div class="col-sm-6">
                    <div class="form-group">
                        <label for="infoCity">City</label>
                        <input type="text" class="form-control" v-model="city" id="infoCity" placeholder="City">
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <label for="infoState">State/Prov.</label>
                        <input type="text" class="form-control" v-model="state" id="infoState" placeholder="State/Province">
                    </div>
                </div>
            </div>

            <div class="row col-sm-offset-2">
                <div class="col-sm-4">
                    <div class="form-group">
                        <label for="infoZip">ZIP/Postal Code</label>
                        <input type="text" class="form-control" v-model="zip" id="infoZip" placeholder="12345">
                    </div>
                </div>
                <div class="col-sm-8">
                    <div class="form-group" :class="{ 'has-error': checkForError('country') }">
                        <label for="country">Country</label>
                        <select name="country" id="country" class="form-control" v-model="country_code" v-validate:country="{ required: true }" >
                                <option value="">-- please select --</option>
                                <option value="af">Afghanistan</option>
                                <option value="ax">Åland Islands</option>
                                <option value="al">Albania</option>
                                <option value="dz">Algeria</option>
                                <option value="as">American Samoa</option>
                                <option value="ad">Andorra</option>
                                <option value="ao">Angola</option>
                                <option value="ai">Anguilla</option>
                                <option value="aq">Antarctica</option>
                                <option value="ag">Antigua and Barbuda</option>
                                <option value="ar">Argentina</option>
                                <option value="am">Armenia</option>
                                <option value="aw">Aruba</option>
                                <option value="au">Australia</option>
                                <option value="at">Austria</option>
                                <option value="az">Azerbaijan</option>
                                <option value="bs">Bahamas</option>
                                <option value="bh">Bahrain</option>
                                <option value="bd">Bangladesh</option>
                                <option value="bb">Barbados</option>
                                <option value="by">Belarus</option>
                                <option value="be">Belgium</option>
                                <option value="bz">Belize</option>
                                <option value="bj">Benin</option>
                                <option value="bm">Bermuda</option>
                                <option value="bt">Bhutan</option>
                                <option value="bo">Bolivia, Plurinational State of</option>
                                <option value="bq">Bonaire, Sint Eustatius and Saba</option>
                                <option value="ba">Bosnia and Herzegovina</option>
                                <option value="bw">Botswana</option>
                                <option value="bv">Bouvet Island</option>
                                <option value="br">Brazil</option>
                                <option value="io">British Indian Ocean Territory</option>
                                <option value="bn">Brunei Darussalam</option>
                                <option value="bg">Bulgaria</option>
                                <option value="bf">Burkina Faso</option>
                                <option value="bi">Burundi</option>
                                <option value="kh">Cambodia</option>
                                <option value="cm">Cameroon</option>
                                <option value="ca">Canada</option>
                                <option value="cv">Cape Verde</option>
                                <option value="ky">Cayman Islands</option>
                                <option value="cf">Central African Republic</option>
                                <option value="td">Chad</option>
                                <option value="cl">Chile</option>
                                <option value="cn">China</option>
                                <option value="cx">Christmas Island</option>
                                <option value="cc">Cocos (Keeling) Islands</option>
                                <option value="co">Colombia</option>
                                <option value="km">Comoros</option>
                                <option value="cg">Congo</option>
                                <option value="cd">Congo, the Democratic Republic of the</option>
                                <option value="ck">Cook Islands</option>
                                <option value="cr">Costa Rica</option>
                                <option value="ci">Côte d'Ivoire</option>
                                <option value="hr">Croatia</option>
                                <option value="cu">Cuba</option>
                                <option value="cw">Curaçao</option>
                                <option value="cy">Cyprus</option>
                                <option value="cz">Czech Republic</option>
                                <option value="dk">Denmark</option>
                                <option value="dj">Djibouti</option>
                                <option value="dm">Dominica</option>
                                <option value="do">Dominican Republic</option>
                                <option value="ec">Ecuador</option>
                                <option value="eg">Egypt</option>
                                <option value="sv">El Salvador</option>
                                <option value="gq">Equatorial Guinea</option>
                                <option value="er">Eritrea</option>
                                <option value="ee">Estonia</option>
                                <option value="et">Ethiopia</option>
                                <option value="fk">Falkland Islands (Malvinas)</option>
                                <option value="fo">Faroe Islands</option>
                                <option value="fj">Fiji</option>
                                <option value="fi">Finland</option>
                                <option value="fr">France</option>
                                <option value="gf">French Guiana</option>
                                <option value="pf">French Polynesia</option>
                                <option value="tf">French Southern Territories</option>
                                <option value="ga">Gabon</option>
                                <option value="gm">Gambia</option>
                                <option value="ge">Georgia</option>
                                <option value="de">Germany</option>
                                <option value="gh">Ghana</option>
                                <option value="gi">Gibraltar</option>
                                <option value="gr">Greece</option>
                                <option value="gl">Greenland</option>
                                <option value="gd">Grenada</option>
                                <option value="gp">Guadeloupe</option>
                                <option value="gu">Guam</option>
                                <option value="gt">Guatemala</option>
                                <option value="gg">Guernsey</option>
                                <option value="gn">Guinea</option>
                                <option value="gw">Guinea-Bissau</option>
                                <option value="gy">Guyana</option>
                                <option value="ht">Haiti</option>
                                <option value="hm">Heard Island and McDonald Islands</option>
                                <option value="va">Holy See (Vatican City State)</option>
                                <option value="hn">Honduras</option>
                                <option value="hk">Hong Kong</option>
                                <option value="hu">Hungary</option>
                                <option value="is">Iceland</option>
                                <option value="in">India</option>
                                <option value="id">Indonesia</option>
                                <option value="ir">Iran, Islamic Republic of</option>
                                <option value="iq">Iraq</option>
                                <option value="ie">Ireland</option>
                                <option value="im">Isle of Man</option>
                                <option value="il">Israel</option>
                                <option value="it">Italy</option>
                                <option value="jm">Jamaica</option>
                                <option value="jp">Japan</option>
                                <option value="je">Jersey</option>
                                <option value="jo">Jordan</option>
                                <option value="kz">Kazakhstan</option>
                                <option value="ke">Kenya</option>
                                <option value="ki">Kiribati</option>
                                <option value="kp">Korea, Democratic People's Republic of</option>
                                <option value="kr">Korea, Republic of</option>
                                <option value="kw">Kuwait</option>
                                <option value="kg">Kyrgyzstan</option>
                                <option value="la">Lao People's Democratic Republic</option>
                                <option value="lv">Latvia</option>
                                <option value="lb">Lebanon</option>
                                <option value="ls">Lesotho</option>
                                <option value="lr">Liberia</option>
                                <option value="ly">Libya</option>
                                <option value="li">Liechtenstein</option>
                                <option value="lt">Lithuania</option>
                                <option value="lu">Luxembourg</option>
                                <option value="mo">Macao</option>
                                <option value="mk">Macedonia, the former Yugoslav Republic of</option>
                                <option value="mg">Madagascar</option>
                                <option value="mw">Malawi</option>
                                <option value="my">Malaysia</option>
                                <option value="mv">Maldives</option>
                                <option value="ml">Mali</option>
                                <option value="mt">Malta</option>
                                <option value="mh">Marshall Islands</option>
                                <option value="mq">Martinique</option>
                                <option value="mr">Mauritania</option>
                                <option value="mu">Mauritius</option>
                                <option value="yt">Mayotte</option>
                                <option value="mx">Mexico</option>
                                <option value="fm">Micronesia, Federated States of</option>
                                <option value="md">Moldova, Republic of</option>
                                <option value="mc">Monaco</option>
                                <option value="mn">Mongolia</option>
                                <option value="me">Montenegro</option>
                                <option value="ms">Montserrat</option>
                                <option value="ma">Morocco</option>
                                <option value="mz">Mozambique</option>
                                <option value="mm">Myanmar</option>
                                <option value="na">Namibia</option>
                                <option value="nr">Nauru</option>
                                <option value="np">Nepal</option>
                                <option value="nl">Netherlands</option>
                                <option value="nc">New Caledonia</option>
                                <option value="nz">New Zealand</option>
                                <option value="ni">Nicaragua</option>
                                <option value="ne">Niger</option>
                                <option value="ng">Nigeria</option>
                                <option value="nu">Niue</option>
                                <option value="nf">Norfolk Island</option>
                                <option value="mp">Northern Mariana Islands</option>
                                <option value="no">Norway</option>
                                <option value="om">Oman</option>
                                <option value="pk">Pakistan</option>
                                <option value="pw">Palau</option>
                                <option value="ps">Palestinian Territory, Occupied</option>
                                <option value="pa">Panama</option>
                                <option value="pg">Papua New Guinea</option>
                                <option value="py">Paraguay</option>
                                <option value="pe">Peru</option>
                                <option value="ph">Philippines</option>
                                <option value="pn">Pitcairn</option>
                                <option value="pl">Poland</option>
                                <option value="pt">Portugal</option>
                                <option value="pr">Puerto Rico</option>
                                <option value="qa">Qatar</option>
                                <option value="re">Réunion</option>
                                <option value="ro">Romania</option>
                                <option value="ru">Russian Federation</option>
                                <option value="rw">Rwanda</option>
                                <option value="bl">Saint Barthélemy</option>
                                <option value="sh">Saint Helena, Ascension and Tristan da Cunha</option>
                                <option value="kn">Saint Kitts and Nevis</option>
                                <option value="lc">Saint Lucia</option>
                                <option value="mf">Saint Martin (French part)</option>
                                <option value="pm">Saint Pierre and Miquelon</option>
                                <option value="vc">Saint Vincent and the Grenadines</option>
                                <option value="ws">Samoa</option>
                                <option value="sm">San Marino</option>
                                <option value="st">Sao Tome and Principe</option>
                                <option value="sa">Saudi Arabia</option>
                                <option value="sn">Senegal</option>
                                <option value="rs">Serbia</option>
                                <option value="sc">Seychelles</option>
                                <option value="sl">Sierra Leone</option>
                                <option value="sg">Singapore</option>
                                <option value="sx">Sint Maarten (Dutch part)</option>
                                <option value="sk">Slovakia</option>
                                <option value="si">Slovenia</option>
                                <option value="sb">Solomon Islands</option>
                                <option value="so">Somalia</option>
                                <option value="za">South Africa</option>
                                <option value="gs">South Georgia and the South Sandwich Islands</option>
                                <option value="ss">South Sudan</option>
                                <option value="es">Spain</option>
                                <option value="lk">Sri Lanka</option>
                                <option value="sd">Sudan</option>
                                <option value="sr">Suriname</option>
                                <option value="sj">Svalbard and Jan Mayen</option>
                                <option value="sz">Swaziland</option>
                                <option value="se">Sweden</option>
                                <option value="ch">Switzerland</option>
                                <option value="sy">Syrian Arab Republic</option>
                                <option value="tw">Taiwan, Province of China</option>
                                <option value="tj">Tajikistan</option>
                                <option value="tz">Tanzania, United Republic of</option>
                                <option value="th">Thailand</option>
                                <option value="tl">Timor-Leste</option>
                                <option value="tg">Togo</option>
                                <option value="tk">Tokelau</option>
                                <option value="to">Tonga</option>
                                <option value="tt">Trinidad and Tobago</option>
                                <option value="tn">Tunisia</option>
                                <option value="tr">Turkey</option>
                                <option value="tm">Turkmenistan</option>
                                <option value="tc">Turks and Caicos Islands</option>
                                <option value="tv">Tuvalu</option>
                                <option value="ug">Uganda</option>
                                <option value="ua">Ukraine</option>
                                <option value="ae">United Arab Emirates</option>
                                <option value="gb">United Kingdom</option>
                                <option value="us">United States</option>
                                <option value="um">United States Minor Outlying Islands</option>
                                <option value="uy">Uruguay</option>
                                <option value="uz">Uzbekistan</option>
                                <option value="vu">Vanuatu</option>
                                <option value="ve">Venezuela, Bolivarian Republic of</option>
                                <option value="vn">Viet Nam</option>
                                <option value="vg">Virgin Islands, British</option>
                                <option value="vi">Virgin Islands, U.S.</option>
                                <option value="wf">Wallis and Futuna</option>
                                <option value="eh">Western Sahara</option>
                                <option value="ye">Yemen</option>
                                <option value="zm">Zambia</option>
                                <option value="zw">Zimbabwe</option>
                            </select>
                    </div>
                </div>
            </div>

            <div class="form-group" :class="{ 'has-error': checkForError('type') }">
                <label for="country" class="col-sm-2 control-label">Type</label>
                <div class="col-sm-10">
                    <select name="type" id="type" class="form-control" v-model="type" v-validate:type="{ required: true }" required>
                        <option value="">-- please select --</option>
                        <option :value="option" v-for="option in typeOptions">{{option|capitalize}}</option>
                    </select>
                </div>
            </div>

            <div class="form-group" :class="{ 'has-error': checkForError('timezone') }">
                <label for="country" class="col-sm-2 control-label">Timezone</label>
                <div class="col-sm-10">
                    <select name="timezone" id="timezone" class="form-control" v-model="timezone" v-validate:timezone="{ required: true }">
                        <option value="">-- please select --</option>
                        <option value="America/Adak">(GMT-10:00) America/Adak (Hawaii-Aleutian Standard Time)</option>
                        <option value="America/Atka">(GMT-10:00) America/Atka (Hawaii-Aleutian Standard Time)</option>
                        <option value="America/Anchorage">(GMT-9:00) America/Anchorage (Alaska Standard Time)</option>
                        <option value="America/Juneau">(GMT-9:00) America/Juneau (Alaska Standard Time)</option>
                        <option value="America/Nome">(GMT-9:00) America/Nome (Alaska Standard Time)</option>
                        <option value="America/Yakutat">(GMT-9:00) America/Yakutat (Alaska Standard Time)</option>
                        <option value="America/Dawson">(GMT-8:00) America/Dawson (Pacific Standard Time)</option>
                        <option value="America/Ensenada">(GMT-8:00) America/Ensenada (Pacific Standard Time)</option>
                        <option value="America/Los_Angeles">(GMT-8:00) America/Los_Angeles (Pacific Standard Time)</option>
                        <option value="America/Tijuana">(GMT-8:00) America/Tijuana (Pacific Standard Time)</option>
                        <option value="America/Vancouver">(GMT-8:00) America/Vancouver (Pacific Standard Time)</option>
                        <option value="America/Whitehorse">(GMT-8:00) America/Whitehorse (Pacific Standard Time)</option>
                        <option value="Canada/Pacific">(GMT-8:00) Canada/Pacific (Pacific Standard Time)</option>
                        <option value="Canada/Yukon">(GMT-8:00) Canada/Yukon (Pacific Standard Time)</option>
                        <option value="Mexico/BajaNorte">(GMT-8:00) Mexico/BajaNorte (Pacific Standard Time)</option>
                        <option value="America/Boise">(GMT-7:00) America/Boise (Mountain Standard Time)</option>
                        <option value="America/Cambridge_Bay">(GMT-7:00) America/Cambridge_Bay (Mountain Standard Time)</option>
                        <option value="America/Chihuahua">(GMT-7:00) America/Chihuahua (Mountain Standard Time)</option>
                        <option value="America/Dawson_Creek">(GMT-7:00) America/Dawson_Creek (Mountain Standard Time)</option>
                        <option value="America/Denver">(GMT-7:00) America/Denver (Mountain Standard Time)</option>
                        <option value="America/Edmonton">(GMT-7:00) America/Edmonton (Mountain Standard Time)</option>
                        <option value="America/Hermosillo">(GMT-7:00) America/Hermosillo (Mountain Standard Time)</option>
                        <option value="America/Inuvik">(GMT-7:00) America/Inuvik (Mountain Standard Time)</option>
                        <option value="America/Mazatlan">(GMT-7:00) America/Mazatlan (Mountain Standard Time)</option>
                        <option value="America/Phoenix">(GMT-7:00) America/Phoenix (Mountain Standard Time)</option>
                        <option value="America/Shiprock">(GMT-7:00) America/Shiprock (Mountain Standard Time)</option>
                        <option value="America/Yellowknife">(GMT-7:00) America/Yellowknife (Mountain Standard Time)</option>
                        <option value="Canada/Mountain">(GMT-7:00) Canada/Mountain (Mountain Standard Time)</option>
                        <option value="Mexico/BajaSur">(GMT-7:00) Mexico/BajaSur (Mountain Standard Time)</option>
                        <option value="America/Belize">(GMT-6:00) America/Belize (Central Standard Time)</option>
                        <option value="America/Cancun">(GMT-6:00) America/Cancun (Central Standard Time)</option>
                        <option value="America/Chicago">(GMT-6:00) America/Chicago (Central Standard Time)</option>
                        <option value="America/Costa_Rica">(GMT-6:00) America/Costa_Rica (Central Standard Time)</option>
                        <option value="America/El_Salvador">(GMT-6:00) America/El_Salvador (Central Standard Time)</option>
                        <option value="America/Guatemala">(GMT-6:00) America/Guatemala (Central Standard Time)</option>
                        <option value="America/Knox_IN">(GMT-6:00) America/Knox_IN (Central Standard Time)</option>
                        <option value="America/Managua">(GMT-6:00) America/Managua (Central Standard Time)</option>
                        <option value="America/Menominee">(GMT-6:00) America/Menominee (Central Standard Time)</option>
                        <option value="America/Merida">(GMT-6:00) America/Merida (Central Standard Time)</option>
                        <option value="America/Mexico_City">(GMT-6:00) America/Mexico_City (Central Standard Time)</option>
                        <option value="America/Monterrey">(GMT-6:00) America/Monterrey (Central Standard Time)</option>
                        <option value="America/Rainy_River">(GMT-6:00) America/Rainy_River (Central Standard Time)</option>
                        <option value="America/Rankin_Inlet">(GMT-6:00) America/Rankin_Inlet (Central Standard Time)</option>
                        <option value="America/Regina">(GMT-6:00) America/Regina (Central Standard Time)</option>
                        <option value="America/Swift_Current">(GMT-6:00) America/Swift_Current (Central Standard Time)</option>
                        <option value="America/Tegucigalpa">(GMT-6:00) America/Tegucigalpa (Central Standard Time)</option>
                        <option value="America/Winnipeg">(GMT-6:00) America/Winnipeg (Central Standard Time)</option>
                        <option value="Canada/Central">(GMT-6:00) Canada/Central (Central Standard Time)</option>
                        <option value="Canada/East-Saskatchewan">(GMT-6:00) Canada/East-Saskatchewan (Central Standard Time)</option>
                        <option value="Canada/Saskatchewan">(GMT-6:00) Canada/Saskatchewan (Central Standard Time)</option>
                        <option value="Chile/EasterIsland">(GMT-6:00) Chile/EasterIsland (Easter Is. Time)</option>
                        <option value="Mexico/General">(GMT-6:00) Mexico/General (Central Standard Time)</option>
                        <option value="America/Atikokan">(GMT-5:00) America/Atikokan (Eastern Standard Time)</option>
                        <option value="America/Bogota">(GMT-5:00) America/Bogota (Colombia Time)</option>
                        <option value="America/Cayman">(GMT-5:00) America/Cayman (Eastern Standard Time)</option>
                        <option value="America/Coral_Harbour">(GMT-5:00) America/Coral_Harbour (Eastern Standard Time)</option>
                        <option value="America/Detroit">(GMT-5:00) America/Detroit (Eastern Standard Time)</option>
                        <option value="America/Fort_Wayne">(GMT-5:00) America/Fort_Wayne (Eastern Standard Time)</option>
                        <option value="America/Grand_Turk">(GMT-5:00) America/Grand_Turk (Eastern Standard Time)</option>
                        <option value="America/Guayaquil">(GMT-5:00) America/Guayaquil (Ecuador Time)</option>
                        <option value="America/Havana">(GMT-5:00) America/Havana (Cuba Standard Time)</option>
                        <option value="America/Indianapolis">(GMT-5:00) America/Indianapolis (Eastern Standard Time)</option>
                        <option value="America/Iqaluit">(GMT-5:00) America/Iqaluit (Eastern Standard Time)</option>
                        <option value="America/Jamaica">(GMT-5:00) America/Jamaica (Eastern Standard Time)</option>
                        <option value="America/Lima">(GMT-5:00) America/Lima (Peru Time)</option>
                        <option value="America/Louisville">(GMT-5:00) America/Louisville (Eastern Standard Time)</option>
                        <option value="America/Montreal">(GMT-5:00) America/Montreal (Eastern Standard Time)</option>
                        <option value="America/Nassau">(GMT-5:00) America/Nassau (Eastern Standard Time)</option>
                        <option value="America/New_York">(GMT-5:00) America/New_York (Eastern Standard Time)</option>
                        <option value="America/Nipigon">(GMT-5:00) America/Nipigon (Eastern Standard Time)</option>
                        <option value="America/Panama">(GMT-5:00) America/Panama (Eastern Standard Time)</option>
                        <option value="America/Pangnirtung">(GMT-5:00) America/Pangnirtung (Eastern Standard Time)</option>
                        <option value="America/Port-au-Prince">(GMT-5:00) America/Port-au-Prince (Eastern Standard Time)</option>
                        <option value="America/Resolute">(GMT-5:00) America/Resolute (Eastern Standard Time)</option>
                        <option value="America/Thunder_Bay">(GMT-5:00) America/Thunder_Bay (Eastern Standard Time)</option>
                        <option value="America/Toronto">(GMT-5:00) America/Toronto (Eastern Standard Time)</option>
                        <option value="Canada/Eastern">(GMT-5:00) Canada/Eastern (Eastern Standard Time)</option>
                        <option value="America/Caracas">(GMT-4:-30) America/Caracas (Venezuela Time)</option>
                        <option value="America/Anguilla">(GMT-4:00) America/Anguilla (Atlantic Standard Time)</option>
                        <option value="America/Antigua">(GMT-4:00) America/Antigua (Atlantic Standard Time)</option>
                        <option value="America/Aruba">(GMT-4:00) America/Aruba (Atlantic Standard Time)</option>
                        <option value="America/Asuncion">(GMT-4:00) America/Asuncion (Paraguay Time)</option>
                        <option value="America/Barbados">(GMT-4:00) America/Barbados (Atlantic Standard Time)</option>
                        <option value="America/Blanc-Sablon">(GMT-4:00) America/Blanc-Sablon (Atlantic Standard Time)</option>
                        <option value="America/Boa_Vista">(GMT-4:00) America/Boa_Vista (Amazon Time)</option>
                        <option value="America/Campo_Grande">(GMT-4:00) America/Campo_Grande (Amazon Time)</option>
                        <option value="America/Cuiaba">(GMT-4:00) America/Cuiaba (Amazon Time)</option>
                        <option value="America/Curacao">(GMT-4:00) America/Curacao (Atlantic Standard Time)</option>
                        <option value="America/Dominica">(GMT-4:00) America/Dominica (Atlantic Standard Time)</option>
                        <option value="America/Eirunepe">(GMT-4:00) America/Eirunepe (Amazon Time)</option>
                        <option value="America/Glace_Bay">(GMT-4:00) America/Glace_Bay (Atlantic Standard Time)</option>
                        <option value="America/Goose_Bay">(GMT-4:00) America/Goose_Bay (Atlantic Standard Time)</option>
                        <option value="America/Grenada">(GMT-4:00) America/Grenada (Atlantic Standard Time)</option>
                        <option value="America/Guadeloupe">(GMT-4:00) America/Guadeloupe (Atlantic Standard Time)</option>
                        <option value="America/Guyana">(GMT-4:00) America/Guyana (Guyana Time)</option>
                        <option value="America/Halifax">(GMT-4:00) America/Halifax (Atlantic Standard Time)</option>
                        <option value="America/La_Paz">(GMT-4:00) America/La_Paz (Bolivia Time)</option>
                        <option value="America/Manaus">(GMT-4:00) America/Manaus (Amazon Time)</option>
                        <option value="America/Marigot">(GMT-4:00) America/Marigot (Atlantic Standard Time)</option>
                        <option value="America/Martinique">(GMT-4:00) America/Martinique (Atlantic Standard Time)</option>
                        <option value="America/Moncton">(GMT-4:00) America/Moncton (Atlantic Standard Time)</option>
                        <option value="America/Montserrat">(GMT-4:00) America/Montserrat (Atlantic Standard Time)</option>
                        <option value="America/Port_of_Spain">(GMT-4:00) America/Port_of_Spain (Atlantic Standard Time)</option>
                        <option value="America/Porto_Acre">(GMT-4:00) America/Porto_Acre (Amazon Time)</option>
                        <option value="America/Porto_Velho">(GMT-4:00) America/Porto_Velho (Amazon Time)</option>
                        <option value="America/Puerto_Rico">(GMT-4:00) America/Puerto_Rico (Atlantic Standard Time)</option>
                        <option value="America/Rio_Branco">(GMT-4:00) America/Rio_Branco (Amazon Time)</option>
                        <option value="America/Santiago">(GMT-4:00) America/Santiago (Chile Time)</option>
                        <option value="America/Santo_Domingo">(GMT-4:00) America/Santo_Domingo (Atlantic Standard Time)</option>
                        <option value="America/St_Barthelemy">(GMT-4:00) America/St_Barthelemy (Atlantic Standard Time)</option>
                        <option value="America/St_Kitts">(GMT-4:00) America/St_Kitts (Atlantic Standard Time)</option>
                        <option value="America/St_Lucia">(GMT-4:00) America/St_Lucia (Atlantic Standard Time)</option>
                        <option value="America/St_Thomas">(GMT-4:00) America/St_Thomas (Atlantic Standard Time)</option>
                        <option value="America/St_Vincent">(GMT-4:00) America/St_Vincent (Atlantic Standard Time)</option>
                        <option value="America/Thule">(GMT-4:00) America/Thule (Atlantic Standard Time)</option>
                        <option value="America/Tortola">(GMT-4:00) America/Tortola (Atlantic Standard Time)</option>
                        <option value="America/Virgin">(GMT-4:00) America/Virgin (Atlantic Standard Time)</option>
                        <option value="Antarctica/Palmer">(GMT-4:00) Antarctica/Palmer (Chile Time)</option>
                        <option value="Atlantic/Bermuda">(GMT-4:00) Atlantic/Bermuda (Atlantic Standard Time)</option>
                        <option value="Atlantic/Stanley">(GMT-4:00) Atlantic/Stanley (Falkland Is. Time)</option>
                        <option value="Brazil/Acre">(GMT-4:00) Brazil/Acre (Amazon Time)</option>
                        <option value="Brazil/West">(GMT-4:00) Brazil/West (Amazon Time)</option>
                        <option value="Canada/Atlantic">(GMT-4:00) Canada/Atlantic (Atlantic Standard Time)</option>
                        <option value="Chile/Continental">(GMT-4:00) Chile/Continental (Chile Time)</option>
                        <option value="America/St_Johns">(GMT-3:-30) America/St_Johns (Newfoundland Standard Time)</option>
                        <option value="Canada/Newfoundland">(GMT-3:-30) Canada/Newfoundland (Newfoundland Standard Time)</option>
                        <option value="America/Araguaina">(GMT-3:00) America/Araguaina (Brasilia Time)</option>
                        <option value="America/Bahia">(GMT-3:00) America/Bahia (Brasilia Time)</option>
                        <option value="America/Belem">(GMT-3:00) America/Belem (Brasilia Time)</option>
                        <option value="America/Buenos_Aires">(GMT-3:00) America/Buenos_Aires (Argentine Time)</option>
                        <option value="America/Catamarca">(GMT-3:00) America/Catamarca (Argentine Time)</option>
                        <option value="America/Cayenne">(GMT-3:00) America/Cayenne (French Guiana Time)</option>
                        <option value="America/Cordoba">(GMT-3:00) America/Cordoba (Argentine Time)</option>
                        <option value="America/Fortaleza">(GMT-3:00) America/Fortaleza (Brasilia Time)</option>
                        <option value="America/Godthab">(GMT-3:00) America/Godthab (Western Greenland Time)</option>
                        <option value="America/Jujuy">(GMT-3:00) America/Jujuy (Argentine Time)</option>
                        <option value="America/Maceio">(GMT-3:00) America/Maceio (Brasilia Time)</option>
                        <option value="America/Mendoza">(GMT-3:00) America/Mendoza (Argentine Time)</option>
                        <option value="America/Miquelon">(GMT-3:00) America/Miquelon (Pierre & Miquelon Standard Time)</option>
                        <option value="America/Montevideo">(GMT-3:00) America/Montevideo (Uruguay Time)</option>
                        <option value="America/Paramaribo">(GMT-3:00) America/Paramaribo (Suriname Time)</option>
                        <option value="America/Recife">(GMT-3:00) America/Recife (Brasilia Time)</option>
                        <option value="America/Rosario">(GMT-3:00) America/Rosario (Argentine Time)</option>
                        <option value="America/Santarem">(GMT-3:00) America/Santarem (Brasilia Time)</option>
                        <option value="America/Sao_Paulo">(GMT-3:00) America/Sao_Paulo (Brasilia Time)</option>
                        <option value="Antarctica/Rothera">(GMT-3:00) Antarctica/Rothera (Rothera Time)</option>
                        <option value="Brazil/East">(GMT-3:00) Brazil/East (Brasilia Time)</option>
                        <option value="America/Noronha">(GMT-2:00) America/Noronha (Fernando de Noronha Time)</option>
                        <option value="Atlantic/South_Georgia">(GMT-2:00) Atlantic/South_Georgia (South Georgia Standard Time)</option>
                        <option value="Brazil/DeNoronha">(GMT-2:00) Brazil/DeNoronha (Fernando de Noronha Time)</option>
                        <option value="America/Scoresbysund">(GMT-1:00) America/Scoresbysund (Eastern Greenland Time)</option>
                        <option value="Atlantic/Azores">(GMT-1:00) Atlantic/Azores (Azores Time)</option>
                        <option value="Atlantic/Cape_Verde">(GMT-1:00) Atlantic/Cape_Verde (Cape Verde Time)</option>
                        <option value="Africa/Abidjan">(GMT+0:00) Africa/Abidjan (Greenwich Mean Time)</option>
                        <option value="Africa/Accra">(GMT+0:00) Africa/Accra (Ghana Mean Time)</option>
                        <option value="Africa/Bamako">(GMT+0:00) Africa/Bamako (Greenwich Mean Time)</option>
                        <option value="Africa/Banjul">(GMT+0:00) Africa/Banjul (Greenwich Mean Time)</option>
                        <option value="Africa/Bissau">(GMT+0:00) Africa/Bissau (Greenwich Mean Time)</option>
                        <option value="Africa/Casablanca">(GMT+0:00) Africa/Casablanca (Western European Time)</option>
                        <option value="Africa/Conakry">(GMT+0:00) Africa/Conakry (Greenwich Mean Time)</option>
                        <option value="Africa/Dakar">(GMT+0:00) Africa/Dakar (Greenwich Mean Time)</option>
                        <option value="Africa/El_Aaiun">(GMT+0:00) Africa/El_Aaiun (Western European Time)</option>
                        <option value="Africa/Freetown">(GMT+0:00) Africa/Freetown (Greenwich Mean Time)</option>
                        <option value="Africa/Lome">(GMT+0:00) Africa/Lome (Greenwich Mean Time)</option>
                        <option value="Africa/Monrovia">(GMT+0:00) Africa/Monrovia (Greenwich Mean Time)</option>
                        <option value="Africa/Nouakchott">(GMT+0:00) Africa/Nouakchott (Greenwich Mean Time)</option>
                        <option value="Africa/Ouagadougou">(GMT+0:00) Africa/Ouagadougou (Greenwich Mean Time)</option>
                        <option value="Africa/Sao_Tome">(GMT+0:00) Africa/Sao_Tome (Greenwich Mean Time)</option>
                        <option value="Africa/Timbuktu">(GMT+0:00) Africa/Timbuktu (Greenwich Mean Time)</option>
                        <option value="America/Danmarkshavn">(GMT+0:00) America/Danmarkshavn (Greenwich Mean Time)</option>
                        <option value="Atlantic/Canary">(GMT+0:00) Atlantic/Canary (Western European Time)</option>
                        <option value="Atlantic/Faeroe">(GMT+0:00) Atlantic/Faeroe (Western European Time)</option>
                        <option value="Atlantic/Faroe">(GMT+0:00) Atlantic/Faroe (Western European Time)</option>
                        <option value="Atlantic/Madeira">(GMT+0:00) Atlantic/Madeira (Western European Time)</option>
                        <option value="Atlantic/Reykjavik">(GMT+0:00) Atlantic/Reykjavik (Greenwich Mean Time)</option>
                        <option value="Atlantic/St_Helena">(GMT+0:00) Atlantic/St_Helena (Greenwich Mean Time)</option>
                        <option value="Europe/Belfast">(GMT+0:00) Europe/Belfast (Greenwich Mean Time)</option>
                        <option value="Europe/Dublin">(GMT+0:00) Europe/Dublin (Greenwich Mean Time)</option>
                        <option value="Europe/Guernsey">(GMT+0:00) Europe/Guernsey (Greenwich Mean Time)</option>
                        <option value="Europe/Isle_of_Man">(GMT+0:00) Europe/Isle_of_Man (Greenwich Mean Time)</option>
                        <option value="Europe/Jersey">(GMT+0:00) Europe/Jersey (Greenwich Mean Time)</option>
                        <option value="Europe/Lisbon">(GMT+0:00) Europe/Lisbon (Western European Time)</option>
                        <option value="Europe/London">(GMT+0:00) Europe/London (Greenwich Mean Time)</option>
                        <option value="Africa/Algiers">(GMT+1:00) Africa/Algiers (Central European Time)</option>
                        <option value="Africa/Bangui">(GMT+1:00) Africa/Bangui (Western African Time)</option>
                        <option value="Africa/Brazzaville">(GMT+1:00) Africa/Brazzaville (Western African Time)</option>
                        <option value="Africa/Ceuta">(GMT+1:00) Africa/Ceuta (Central European Time)</option>
                        <option value="Africa/Douala">(GMT+1:00) Africa/Douala (Western African Time)</option>
                        <option value="Africa/Kinshasa">(GMT+1:00) Africa/Kinshasa (Western African Time)</option>
                        <option value="Africa/Lagos">(GMT+1:00) Africa/Lagos (Western African Time)</option>
                        <option value="Africa/Libreville">(GMT+1:00) Africa/Libreville (Western African Time)</option>
                        <option value="Africa/Luanda">(GMT+1:00) Africa/Luanda (Western African Time)</option>
                        <option value="Africa/Malabo">(GMT+1:00) Africa/Malabo (Western African Time)</option>
                        <option value="Africa/Ndjamena">(GMT+1:00) Africa/Ndjamena (Western African Time)</option>
                        <option value="Africa/Niamey">(GMT+1:00) Africa/Niamey (Western African Time)</option>
                        <option value="Africa/Porto-Novo">(GMT+1:00) Africa/Porto-Novo (Western African Time)</option>
                        <option value="Africa/Tunis">(GMT+1:00) Africa/Tunis (Central European Time)</option>
                        <option value="Africa/Windhoek">(GMT+1:00) Africa/Windhoek (Western African Time)</option>
                        <option value="Arctic/Longyearbyen">(GMT+1:00) Arctic/Longyearbyen (Central European Time)</option>
                        <option value="Atlantic/Jan_Mayen">(GMT+1:00) Atlantic/Jan_Mayen (Central European Time)</option>
                        <option value="Europe/Amsterdam">(GMT+1:00) Europe/Amsterdam (Central European Time)</option>
                        <option value="Europe/Andorra">(GMT+1:00) Europe/Andorra (Central European Time)</option>
                        <option value="Europe/Belgrade">(GMT+1:00) Europe/Belgrade (Central European Time)</option>
                        <option value="Europe/Berlin">(GMT+1:00) Europe/Berlin (Central European Time)</option>
                        <option value="Europe/Bratislava">(GMT+1:00) Europe/Bratislava (Central European Time)</option>
                        <option value="Europe/Brussels">(GMT+1:00) Europe/Brussels (Central European Time)</option>
                        <option value="Europe/Budapest">(GMT+1:00) Europe/Budapest (Central European Time)</option>
                        <option value="Europe/Copenhagen">(GMT+1:00) Europe/Copenhagen (Central European Time)</option>
                        <option value="Europe/Gibraltar">(GMT+1:00) Europe/Gibraltar (Central European Time)</option>
                        <option value="Europe/Ljubljana">(GMT+1:00) Europe/Ljubljana (Central European Time)</option>
                        <option value="Europe/Luxembourg">(GMT+1:00) Europe/Luxembourg (Central European Time)</option>
                        <option value="Europe/Madrid">(GMT+1:00) Europe/Madrid (Central European Time)</option>
                        <option value="Europe/Malta">(GMT+1:00) Europe/Malta (Central European Time)</option>
                        <option value="Europe/Monaco">(GMT+1:00) Europe/Monaco (Central European Time)</option>
                        <option value="Europe/Oslo">(GMT+1:00) Europe/Oslo (Central European Time)</option>
                        <option value="Europe/Paris">(GMT+1:00) Europe/Paris (Central European Time)</option>
                        <option value="Europe/Podgorica">(GMT+1:00) Europe/Podgorica (Central European Time)</option>
                        <option value="Europe/Prague">(GMT+1:00) Europe/Prague (Central European Time)</option>
                        <option value="Europe/Rome">(GMT+1:00) Europe/Rome (Central European Time)</option>
                        <option value="Europe/San_Marino">(GMT+1:00) Europe/San_Marino (Central European Time)</option>
                        <option value="Europe/Sarajevo">(GMT+1:00) Europe/Sarajevo (Central European Time)</option>
                        <option value="Europe/Skopje">(GMT+1:00) Europe/Skopje (Central European Time)</option>
                        <option value="Europe/Stockholm">(GMT+1:00) Europe/Stockholm (Central European Time)</option>
                        <option value="Europe/Tirane">(GMT+1:00) Europe/Tirane (Central European Time)</option>
                        <option value="Europe/Vaduz">(GMT+1:00) Europe/Vaduz (Central European Time)</option>
                        <option value="Europe/Vatican">(GMT+1:00) Europe/Vatican (Central European Time)</option>
                        <option value="Europe/Vienna">(GMT+1:00) Europe/Vienna (Central European Time)</option>
                        <option value="Europe/Warsaw">(GMT+1:00) Europe/Warsaw (Central European Time)</option>
                        <option value="Europe/Zagreb">(GMT+1:00) Europe/Zagreb (Central European Time)</option>
                        <option value="Europe/Zurich">(GMT+1:00) Europe/Zurich (Central European Time)</option>
                        <option value="Africa/Blantyre">(GMT+2:00) Africa/Blantyre (Central African Time)</option>
                        <option value="Africa/Bujumbura">(GMT+2:00) Africa/Bujumbura (Central African Time)</option>
                        <option value="Africa/Cairo">(GMT+2:00) Africa/Cairo (Eastern European Time)</option>
                        <option value="Africa/Gaborone">(GMT+2:00) Africa/Gaborone (Central African Time)</option>
                        <option value="Africa/Harare">(GMT+2:00) Africa/Harare (Central African Time)</option>
                        <option value="Africa/Johannesburg">(GMT+2:00) Africa/Johannesburg (South Africa Standard Time)</option>
                        <option value="Africa/Kigali">(GMT+2:00) Africa/Kigali (Central African Time)</option>
                        <option value="Africa/Lubumbashi">(GMT+2:00) Africa/Lubumbashi (Central African Time)</option>
                        <option value="Africa/Lusaka">(GMT+2:00) Africa/Lusaka (Central African Time)</option>
                        <option value="Africa/Maputo">(GMT+2:00) Africa/Maputo (Central African Time)</option>
                        <option value="Africa/Maseru">(GMT+2:00) Africa/Maseru (South Africa Standard Time)</option>
                        <option value="Africa/Mbabane">(GMT+2:00) Africa/Mbabane (South Africa Standard Time)</option>
                        <option value="Africa/Tripoli">(GMT+2:00) Africa/Tripoli (Eastern European Time)</option>
                        <option value="Asia/Amman">(GMT+2:00) Asia/Amman (Eastern European Time)</option>
                        <option value="Asia/Beirut">(GMT+2:00) Asia/Beirut (Eastern European Time)</option>
                        <option value="Asia/Damascus">(GMT+2:00) Asia/Damascus (Eastern European Time)</option>
                        <option value="Asia/Gaza">(GMT+2:00) Asia/Gaza (Eastern European Time)</option>
                        <option value="Asia/Istanbul">(GMT+2:00) Asia/Istanbul (Eastern European Time)</option>
                        <option value="Asia/Jerusalem">(GMT+2:00) Asia/Jerusalem (Israel Standard Time)</option>
                        <option value="Asia/Nicosia">(GMT+2:00) Asia/Nicosia (Eastern European Time)</option>
                        <option value="Asia/Tel_Aviv">(GMT+2:00) Asia/Tel_Aviv (Israel Standard Time)</option>
                        <option value="Europe/Athens">(GMT+2:00) Europe/Athens (Eastern European Time)</option>
                        <option value="Europe/Bucharest">(GMT+2:00) Europe/Bucharest (Eastern European Time)</option>
                        <option value="Europe/Chisinau">(GMT+2:00) Europe/Chisinau (Eastern European Time)</option>
                        <option value="Europe/Helsinki">(GMT+2:00) Europe/Helsinki (Eastern European Time)</option>
                        <option value="Europe/Istanbul">(GMT+2:00) Europe/Istanbul (Eastern European Time)</option>
                        <option value="Europe/Kaliningrad">(GMT+2:00) Europe/Kaliningrad (Eastern European Time)</option>
                        <option value="Europe/Kiev">(GMT+2:00) Europe/Kiev (Eastern European Time)</option>
                        <option value="Europe/Mariehamn">(GMT+2:00) Europe/Mariehamn (Eastern European Time)</option>
                        <option value="Europe/Minsk">(GMT+2:00) Europe/Minsk (Eastern European Time)</option>
                        <option value="Europe/Nicosia">(GMT+2:00) Europe/Nicosia (Eastern European Time)</option>
                        <option value="Europe/Riga">(GMT+2:00) Europe/Riga (Eastern European Time)</option>
                        <option value="Europe/Simferopol">(GMT+2:00) Europe/Simferopol (Eastern European Time)</option>
                        <option value="Europe/Sofia">(GMT+2:00) Europe/Sofia (Eastern European Time)</option>
                        <option value="Europe/Tallinn">(GMT+2:00) Europe/Tallinn (Eastern European Time)</option>
                        <option value="Europe/Tiraspol">(GMT+2:00) Europe/Tiraspol (Eastern European Time)</option>
                        <option value="Europe/Uzhgorod">(GMT+2:00) Europe/Uzhgorod (Eastern European Time)</option>
                        <option value="Europe/Vilnius">(GMT+2:00) Europe/Vilnius (Eastern European Time)</option>
                        <option value="Europe/Zaporozhye">(GMT+2:00) Europe/Zaporozhye (Eastern European Time)</option>
                        <option value="Africa/Addis_Ababa">(GMT+3:00) Africa/Addis_Ababa (Eastern African Time)</option>
                        <option value="Africa/Asmara">(GMT+3:00) Africa/Asmara (Eastern African Time)</option>
                        <option value="Africa/Asmera">(GMT+3:00) Africa/Asmera (Eastern African Time)</option>
                        <option value="Africa/Dar_es_Salaam">(GMT+3:00) Africa/Dar_es_Salaam (Eastern African Time)</option>
                        <option value="Africa/Djibouti">(GMT+3:00) Africa/Djibouti (Eastern African Time)</option>
                        <option value="Africa/Kampala">(GMT+3:00) Africa/Kampala (Eastern African Time)</option>
                        <option value="Africa/Khartoum">(GMT+3:00) Africa/Khartoum (Eastern African Time)</option>
                        <option value="Africa/Mogadishu">(GMT+3:00) Africa/Mogadishu (Eastern African Time)</option>
                        <option value="Africa/Nairobi">(GMT+3:00) Africa/Nairobi (Eastern African Time)</option>
                        <option value="Antarctica/Syowa">(GMT+3:00) Antarctica/Syowa (Syowa Time)</option>
                        <option value="Asia/Aden">(GMT+3:00) Asia/Aden (Arabia Standard Time)</option>
                        <option value="Asia/Baghdad">(GMT+3:00) Asia/Baghdad (Arabia Standard Time)</option>
                        <option value="Asia/Bahrain">(GMT+3:00) Asia/Bahrain (Arabia Standard Time)</option>
                        <option value="Asia/Kuwait">(GMT+3:00) Asia/Kuwait (Arabia Standard Time)</option>
                        <option value="Asia/Qatar">(GMT+3:00) Asia/Qatar (Arabia Standard Time)</option>
                        <option value="Europe/Moscow">(GMT+3:00) Europe/Moscow (Moscow Standard Time)</option>
                        <option value="Europe/Volgograd">(GMT+3:00) Europe/Volgograd (Volgograd Time)</option>
                        <option value="Indian/Antananarivo">(GMT+3:00) Indian/Antananarivo (Eastern African Time)</option>
                        <option value="Indian/Comoro">(GMT+3:00) Indian/Comoro (Eastern African Time)</option>
                        <option value="Indian/Mayotte">(GMT+3:00) Indian/Mayotte (Eastern African Time)</option>
                        <option value="Asia/Tehran">(GMT+3:30) Asia/Tehran (Iran Standard Time)</option>
                        <option value="Asia/Baku">(GMT+4:00) Asia/Baku (Azerbaijan Time)</option>
                        <option value="Asia/Dubai">(GMT+4:00) Asia/Dubai (Gulf Standard Time)</option>
                        <option value="Asia/Muscat">(GMT+4:00) Asia/Muscat (Gulf Standard Time)</option>
                        <option value="Asia/Tbilisi">(GMT+4:00) Asia/Tbilisi (Georgia Time)</option>
                        <option value="Asia/Yerevan">(GMT+4:00) Asia/Yerevan (Armenia Time)</option>
                        <option value="Europe/Samara">(GMT+4:00) Europe/Samara (Samara Time)</option>
                        <option value="Indian/Mahe">(GMT+4:00) Indian/Mahe (Seychelles Time)</option>
                        <option value="Indian/Mauritius">(GMT+4:00) Indian/Mauritius (Mauritius Time)</option>
                        <option value="Indian/Reunion">(GMT+4:00) Indian/Reunion (Reunion Time)</option>
                        <option value="Asia/Kabul">(GMT+4:30) Asia/Kabul (Afghanistan Time)</option>
                        <option value="Asia/Aqtau">(GMT+5:00) Asia/Aqtau (Aqtau Time)</option>
                        <option value="Asia/Aqtobe">(GMT+5:00) Asia/Aqtobe (Aqtobe Time)</option>
                        <option value="Asia/Ashgabat">(GMT+5:00) Asia/Ashgabat (Turkmenistan Time)</option>
                        <option value="Asia/Ashkhabad">(GMT+5:00) Asia/Ashkhabad (Turkmenistan Time)</option>
                        <option value="Asia/Dushanbe">(GMT+5:00) Asia/Dushanbe (Tajikistan Time)</option>
                        <option value="Asia/Karachi">(GMT+5:00) Asia/Karachi (Pakistan Time)</option>
                        <option value="Asia/Oral">(GMT+5:00) Asia/Oral (Oral Time)</option>
                        <option value="Asia/Samarkand">(GMT+5:00) Asia/Samarkand (Uzbekistan Time)</option>
                        <option value="Asia/Tashkent">(GMT+5:00) Asia/Tashkent (Uzbekistan Time)</option>
                        <option value="Asia/Yekaterinburg">(GMT+5:00) Asia/Yekaterinburg (Yekaterinburg Time)</option>
                        <option value="Indian/Kerguelen">(GMT+5:00) Indian/Kerguelen (French Southern & Antarctic Lands Time)</option>
                        <option value="Indian/Maldives">(GMT+5:00) Indian/Maldives (Maldives Time)</option>
                        <option value="Asia/Calcutta">(GMT+5:30) Asia/Calcutta (India Standard Time)</option>
                        <option value="Asia/Colombo">(GMT+5:30) Asia/Colombo (India Standard Time)</option>
                        <option value="Asia/Kolkata">(GMT+5:30) Asia/Kolkata (India Standard Time)</option>
                        <option value="Asia/Katmandu">(GMT+5:45) Asia/Katmandu (Nepal Time)</option>
                        <option value="Antarctica/Mawson">(GMT+6:00) Antarctica/Mawson (Mawson Time)</option>
                        <option value="Antarctica/Vostok">(GMT+6:00) Antarctica/Vostok (Vostok Time)</option>
                        <option value="Asia/Almaty">(GMT+6:00) Asia/Almaty (Alma-Ata Time)</option>
                        <option value="Asia/Bishkek">(GMT+6:00) Asia/Bishkek (Kirgizstan Time)</option>
                        <option value="Asia/Dacca">(GMT+6:00) Asia/Dacca (Bangladesh Time)</option>
                        <option value="Asia/Dhaka">(GMT+6:00) Asia/Dhaka (Bangladesh Time)</option>
                        <option value="Asia/Novosibirsk">(GMT+6:00) Asia/Novosibirsk (Novosibirsk Time)</option>
                        <option value="Asia/Omsk">(GMT+6:00) Asia/Omsk (Omsk Time)</option>
                        <option value="Asia/Qyzylorda">(GMT+6:00) Asia/Qyzylorda (Qyzylorda Time)</option>
                        <option value="Asia/Thimbu">(GMT+6:00) Asia/Thimbu (Bhutan Time)</option>
                        <option value="Asia/Thimphu">(GMT+6:00) Asia/Thimphu (Bhutan Time)</option>
                        <option value="Indian/Chagos">(GMT+6:00) Indian/Chagos (Indian Ocean Territory Time)</option>
                        <option value="Asia/Rangoon">(GMT+6:30) Asia/Rangoon (Myanmar Time)</option>
                        <option value="Indian/Cocos">(GMT+6:30) Indian/Cocos (Cocos Islands Time)</option>
                        <option value="Antarctica/Davis">(GMT+7:00) Antarctica/Davis (Davis Time)</option>
                        <option value="Asia/Bangkok">(GMT+7:00) Asia/Bangkok (Indochina Time)</option>
                        <option value="Asia/Ho_Chi_Minh">(GMT+7:00) Asia/Ho_Chi_Minh (Indochina Time)</option>
                        <option value="Asia/Hovd">(GMT+7:00) Asia/Hovd (Hovd Time)</option>
                        <option value="Asia/Jakarta">(GMT+7:00) Asia/Jakarta (West Indonesia Time)</option>
                        <option value="Asia/Krasnoyarsk">(GMT+7:00) Asia/Krasnoyarsk (Krasnoyarsk Time)</option>
                        <option value="Asia/Phnom_Penh">(GMT+7:00) Asia/Phnom_Penh (Indochina Time)</option>
                        <option value="Asia/Pontianak">(GMT+7:00) Asia/Pontianak (West Indonesia Time)</option>
                        <option value="Asia/Saigon">(GMT+7:00) Asia/Saigon (Indochina Time)</option>
                        <option value="Asia/Vientiane">(GMT+7:00) Asia/Vientiane (Indochina Time)</option>
                        <option value="Indian/Christmas">(GMT+7:00) Indian/Christmas (Christmas Island Time)</option>
                        <option value="Antarctica/Casey">(GMT+8:00) Antarctica/Casey (Western Standard Time (Australia))</option>
                        <option value="Asia/Brunei">(GMT+8:00) Asia/Brunei (Brunei Time)</option>
                        <option value="Asia/Choibalsan">(GMT+8:00) Asia/Choibalsan (Choibalsan Time)</option>
                        <option value="Asia/Chongqing">(GMT+8:00) Asia/Chongqing (China Standard Time)</option>
                        <option value="Asia/Chungking">(GMT+8:00) Asia/Chungking (China Standard Time)</option>
                        <option value="Asia/Harbin">(GMT+8:00) Asia/Harbin (China Standard Time)</option>
                        <option value="Asia/Hong_Kong">(GMT+8:00) Asia/Hong_Kong (Hong Kong Time)</option>
                        <option value="Asia/Irkutsk">(GMT+8:00) Asia/Irkutsk (Irkutsk Time)</option>
                        <option value="Asia/Kashgar">(GMT+8:00) Asia/Kashgar (China Standard Time)</option>
                        <option value="Asia/Kuala_Lumpur">(GMT+8:00) Asia/Kuala_Lumpur (Malaysia Time)</option>
                        <option value="Asia/Kuching">(GMT+8:00) Asia/Kuching (Malaysia Time)</option>
                        <option value="Asia/Macao">(GMT+8:00) Asia/Macao (China Standard Time)</option>
                        <option value="Asia/Macau">(GMT+8:00) Asia/Macau (China Standard Time)</option>
                        <option value="Asia/Makassar">(GMT+8:00) Asia/Makassar (Central Indonesia Time)</option>
                        <option value="Asia/Manila">(GMT+8:00) Asia/Manila (Philippines Time)</option>
                        <option value="Asia/Shanghai">(GMT+8:00) Asia/Shanghai (China Standard Time)</option>
                        <option value="Asia/Singapore">(GMT+8:00) Asia/Singapore (Singapore Time)</option>
                        <option value="Asia/Taipei">(GMT+8:00) Asia/Taipei (China Standard Time)</option>
                        <option value="Asia/Ujung_Pandang">(GMT+8:00) Asia/Ujung_Pandang (Central Indonesia Time)</option>
                        <option value="Asia/Ulaanbaatar">(GMT+8:00) Asia/Ulaanbaatar (Ulaanbaatar Time)</option>
                        <option value="Asia/Ulan_Bator">(GMT+8:00) Asia/Ulan_Bator (Ulaanbaatar Time)</option>
                        <option value="Asia/Urumqi">(GMT+8:00) Asia/Urumqi (China Standard Time)</option>
                        <option value="Australia/Perth">(GMT+8:00) Australia/Perth (Western Standard Time (Australia))</option>
                        <option value="Australia/West">(GMT+8:00) Australia/West (Western Standard Time (Australia))</option>
                        <option value="Australia/Eucla">(GMT+8:45) Australia/Eucla (Central Western Standard Time (Australia))</option>
                        <option value="Asia/Dili">(GMT+9:00) Asia/Dili (Timor-Leste Time)</option>
                        <option value="Asia/Jayapura">(GMT+9:00) Asia/Jayapura (East Indonesia Time)</option>
                        <option value="Asia/Pyongyang">(GMT+9:00) Asia/Pyongyang (Korea Standard Time)</option>
                        <option value="Asia/Seoul">(GMT+9:00) Asia/Seoul (Korea Standard Time)</option>
                        <option value="Asia/Tokyo">(GMT+9:00) Asia/Tokyo (Japan Standard Time)</option>
                        <option value="Asia/Yakutsk">(GMT+9:00) Asia/Yakutsk (Yakutsk Time)</option>
                        <option value="Australia/Adelaide">(GMT+9:30) Australia/Adelaide (Central Standard Time (South Australia))</option>
                        <option value="Australia/Broken_Hill">(GMT+9:30) Australia/Broken_Hill (Central Standard Time (South Australia/New South Wales))</option>
                        <option value="Australia/Darwin">(GMT+9:30) Australia/Darwin (Central Standard Time (Northern Territory))</option>
                        <option value="Australia/North">(GMT+9:30) Australia/North (Central Standard Time (Northern Territory))</option>
                        <option value="Australia/South">(GMT+9:30) Australia/South (Central Standard Time (South Australia))</option>
                        <option value="Australia/Yancowinna">(GMT+9:30) Australia/Yancowinna (Central Standard Time (South Australia/New South Wales))</option>
                        <option value="Antarctica/DumontDUrville">(GMT+10:00) Antarctica/DumontDUrville (Dumont-d'Urville Time)</option>
                        <option value="Asia/Sakhalin">(GMT+10:00) Asia/Sakhalin (Sakhalin Time)</option>
                        <option value="Asia/Vladivostok">(GMT+10:00) Asia/Vladivostok (Vladivostok Time)</option>
                        <option value="Australia/ACT">(GMT+10:00) Australia/ACT (Eastern Standard Time (New South Wales))</option>
                        <option value="Australia/Brisbane">(GMT+10:00) Australia/Brisbane (Eastern Standard Time (Queensland))</option>
                        <option value="Australia/Canberra">(GMT+10:00) Australia/Canberra (Eastern Standard Time (New South Wales))</option>
                        <option value="Australia/Currie">(GMT+10:00) Australia/Currie (Eastern Standard Time (New South Wales))</option>
                        <option value="Australia/Hobart">(GMT+10:00) Australia/Hobart (Eastern Standard Time (Tasmania))</option>
                        <option value="Australia/Lindeman">(GMT+10:00) Australia/Lindeman (Eastern Standard Time (Queensland))</option>
                        <option value="Australia/Melbourne">(GMT+10:00) Australia/Melbourne (Eastern Standard Time (Victoria))</option>
                        <option value="Australia/NSW">(GMT+10:00) Australia/NSW (Eastern Standard Time (New South Wales))</option>
                        <option value="Australia/Queensland">(GMT+10:00) Australia/Queensland (Eastern Standard Time (Queensland))</option>
                        <option value="Australia/Sydney">(GMT+10:00) Australia/Sydney (Eastern Standard Time (New South Wales))</option>
                        <option value="Australia/Tasmania">(GMT+10:00) Australia/Tasmania (Eastern Standard Time (Tasmania))</option>
                        <option value="Australia/Victoria">(GMT+10:00) Australia/Victoria (Eastern Standard Time (Victoria))</option>
                        <option value="Australia/LHI">(GMT+10:30) Australia/LHI (Lord Howe Standard Time)</option>
                        <option value="Australia/Lord_Howe">(GMT+10:30) Australia/Lord_Howe (Lord Howe Standard Time)</option>
                        <option value="Asia/Magadan">(GMT+11:00) Asia/Magadan (Magadan Time)</option>
                        <option value="Antarctica/McMurdo">(GMT+12:00) Antarctica/McMurdo (New Zealand Standard Time)</option>
                        <option value="Antarctica/South_Pole">(GMT+12:00) Antarctica/South_Pole (New Zealand Standard Time)</option>
                        <option value="Asia/Anadyr">(GMT+12:00) Asia/Anadyr (Anadyr Time)</option>
                        <option value="Asia/Kamchatka">(GMT+12:00) Asia/Kamchatka (Petropavlovsk-Kamchatski Time)</option>
                    </select>

                </div>
            </div>

            <div class="row col-sm-offset-2">
                <div class="col-sm-6">
                    <div class="form-group">
                        <label for="infoPhone">Phone 1</label>
                        <input type="text" class="form-control" v-model="phone_one | phone" id="infoPhone" placeholder="123-456-7890">
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <label for="infoMobile">Phone 2</label>
                        <input type="text" class="form-control" v-model="phone_two | phone" id="infoMobile" placeholder="123-456-7890">
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label for="name" class="col-sm-2 control-label">Email</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" name="email" id="email" v-model="email">
                </div>
            </div>

            <div class="form-group">
                <label for="status" class="col-sm-2 control-label">Status</label>
                <div class="col-sm-10">
                    <label class="radio-inline">
                        <input type="radio" name="status" id="status" :value="true" v-model="public"> Public
                    </label>
                    <label class="radio-inline">
                        <input type="radio" name="status2" id="status2" :value="false" v-model="public"> Private
                    </label>
                </div>
            </div>
            <template v-if="!!public">
                <div class="form-group" :class="{ 'has-error': checkForError('url') }">
                    <label for="url" class="col-sm-2 control-label">Url Slug</label>
                    <div class="col-sm-10">
                        <div class="input-group">
                            <span class="input-group-addon">www.missions.me/groups/</span>
                            <input type="text" id="url" v-model="url" class="form-control" v-validate:url="{ required: !!public }"/>
                        </div>
                    </div>
                </div>
            </template>

            <div class="form-group">
                <div class="col-sm-offset-2 col-sm-10">
                    <a href="/admin/groups" class="btn btn-default">Cancel</a>
                    <a @click="submit()" class="btn btn-primary">Update</a>
                </div>
            </div>
        </form>
    </validator>
</template>
<script>
    export default{
        name: 'group-edit',
        props: ['groupId'],
        data(){
            return {
                name: '',
                description: '',
                type: '',
                country_code: '',
                timezone: '',
                phone_one: '',
                phone_two: '',
                address_one: '',
                address_two: '',
                city: '',
                state: '',
                zip: '',
                public: false,
                url: '',
                email: '',

                // logic variables
                typeOptions: ['church', 'business', 'nonprofit', 'youth', 'other'],
                attemptSubmit: false,
                resource: this.$resource('groups{/id}')

            }
        },
        methods: {
            checkForError(field){
                // if user clicked submit button while the field is invalid trigger error styles 
                return this.$UpdateGroup[field].invalid && this.attemptSubmit;
            },
            submit(){
                this.attemptSubmit = true;
                if (this.$UpdateGroup.valid) {
                    var formData = this.data
                    this.resource.update({id: this.groupId}, {
                        name: this.name,
                        description: this.description,
                        type: this.type,
                        country_code: this.country_code,
                        timezone: this.timezone,
                        phone_one: this.phone_one,
                        phone_two: this.phone_two,
                        address_one: this.address_one,
                        address_two: this.address_two,
                        city: this.city,
                        state: this.state,
                        zip: this.zip,
                        public: this.public,
                        url: this.url,
                        email: this.email
                    }).then(function (resp) {
                        window.location.href = '/admin' + resp.data.data.links[0].uri;
                    }, function (error) {
                        console.log(error);
                        debugger;
                    });
                }
            }
        },
        ready() {
            this.resource.get({id: this.groupId}).then(function (response) {
                $.extend(this.$data, response.data.data);
            }, function (response) {
                console.log('Update Failed! :(');
                console.log(response);
            })
        }
    }
</script> 