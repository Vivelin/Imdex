require 'sinatra'
require 'omniauth'
require 'omniauth-github'
require_relative 'app/imdex'

use Rack::Session::Cookie, {
  key: 'imdex.session',
  secret: ENV['SESSION_SECRET'] || 'dicks', 
  expire_after: 2592000 # 30 days
}

use OmniAuth::Builder do
  provider :github, ENV['GITHUB_KEY'], ENV['GITHUB_SECRET'], scope: 'user:email'
end

run Sinatra::Application