require 'sinatra'
require 'better_errors'
require 'yaml'
require 'tilt/sass'
require 'tilt/haml'

require_relative 'directory'
require_relative 'directory_controller'

configure :development do
  use BetterErrors::Middleware
  BetterErrors.application_root = File.dirname(__dir__)
end

helpers do
  def u(value)
    URI.encode(value).gsub('+', '%2B')
  end
end

configure do
  config = {}
  config = YAML.load_file('config.yml') if File.file?('config.yml')
  set :basedir, File.expand_path(config['basedir'] || 'public')

  set :views, 'templates'
  set :root, File.expand_path(File.dirname(__dir__))
  set :public_folder, settings.basedir
  set :haml, escape_html: true
end

get '/styles/:name' do
  sass params[:name].to_sym
end

get '/favicon.ico' do
  send_file 'assets/favicon.ico'
end

get '/debug' do

end

get '/*' do
  requested_path = URI.decode(request.path_info[1..-1])
  path = File.expand_path(requested_path, settings.basedir)
  pass unless path.start_with?(settings.basedir)
  pass unless File.exist?(path)

  # Serve files when nginx for some reason refuses to do so, e.g. Cave Story+
  halt send_file(path) if File.file?(path)

  directory = Imdex::Directory.new(path)
  controller = Imdex::DirectoryController.new(directory, settings)
  haml :directory, locals: { directory: controller }
end