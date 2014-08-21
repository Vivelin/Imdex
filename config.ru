require "omniauth-openid"
require "./lib/imdex_app"

use Rack::Session::Cookie, {
  :key => "imdex.session",
  :secret => "dicks", 
  :expire_after => 2592000 # 30 days
}

use OmniAuth::Builder do
  provider :open_id, :name => "google", 
    :identifier => "https://www.google.com/accounts/o8/id"
end

run ImdexApp