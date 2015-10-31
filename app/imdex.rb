require 'sinatra'
require 'better_errors'
require 'tilt/sass'
require 'tilt/haml'

require_relative 'helpers'
require_relative 'config'
require_relative 'directory'
require_relative 'directory_controller'
require_relative 'user_controller'

helpers Imdex::Helpers

configure :development do
  use BetterErrors::Middleware
  BetterErrors.application_root = File.dirname(__dir__)
end

configure do
  set :config, Imdex::Config.new('config.yml')

  set :views, 'templates'
  set :root, File.expand_path(File.dirname(__dir__))
  set :public_folder, settings.config.basedir
  set :haml, escape_html: true
end

get '/auth/:provider/callback' do
  info = request.env["omniauth.auth"]["info"]
  puts info

  session[:user] = info['email']
  session[:name] = info['name']
  session[:avatar] = info['image']
  redirect to('/'), 303
end

post "/auth/failure" do
  puts "auth failure"
  session.clear
  redirect to("/"), 303
end

post "/logout" do
  session.clear
  redirect to("/"), 303
end

get '/styles/:name' do
  name = params[:name].to_sym

  last_modified mtime(name, :sass)
  sass name
end

get '/assets/blazy.min.js' do
  send_file 'assets/blazy.min.js'
end

get '/favicon.ico' do
  send_file 'assets/favicon.ico'
end

get '/*' do
  requested_path = URI.decode(request.path_info[1..-1])
  path = File.expand_path(requested_path, settings.config.basedir)
  pass unless path.start_with?(settings.config.basedir)
  pass unless File.exist?(path)

  # Serve files when nginx for some reason refuses to do so, e.g. Cave Story+
  if File.file?(path)
    puts "WARNING: serving #{ request.path } from code because nginx is too lazy"
    halt send_file(path) 
  end

  directory = Imdex::Directory.new(path)
  dir_controller = Imdex::DirectoryController.new(directory, settings.config)
  user_controller = Imdex::UserController.new(session, settings.config)
  haml :directory, locals: { directory: dir_controller, user: user_controller }
end