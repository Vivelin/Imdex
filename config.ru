require "omniauth-github"
require "./lib/imdex_app"

use Rack::Session::Cookie, {
  :key => "imdex.session",
  :secret => ENV["SESSION_SECRET"] || "dicks", 
  :expire_after => 2592000 # 30 days
}

run ImdexApp