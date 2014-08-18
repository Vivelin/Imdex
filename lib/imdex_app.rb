require "sinatra/base"
require "better_errors"
require "sass"
require "yaml"

require "./lib/helpers/template_utils"
require "./lib/imdex/directory"

class ImdexApp < Sinatra::Base
  helpers Helpers::TemplateUtils

  configure do
    # Sinatra settings
    set :views, :sass => "styles", :default => "views"
    set :root, File.expand_path(File.dirname(File.dirname(__FILE__)))
    enable :static

    # App settings
    set :app_config, YAML.load_file("config/application.yaml") || {}

    # Directory settings
    Imdex::Directory.public_folder = settings.public_folder
    Imdex::Directory.include_pattern = Regexp.new(settings.app_config["include"]  || "\.(jpe?g|png|gif)$")
    Imdex::Directory.ignore_list = settings.app_config["ignore"] || [ "\\/\\.\\.?", "^\\/assets" ]
  end

  configure :development do
    use BetterErrors::Middleware
    BetterErrors.application_root = __dir__
  end

  get "/assets/style" do
    sass :main
  end

  get "/*" do
    dir = Imdex::Directory.new(request.path)

    @name = dir.name
    erb :directory, :locals => { :dir => dir }
  end
end