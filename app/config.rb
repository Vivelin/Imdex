require 'yaml'

module Imdex
  class Config
    def initialize(path)
      @config = YAML.load_file(path)
    end

    def [](n)
      @config[n]
    end

    def basedir
      File.expand_path(@config['basedir'] || 'public')
    end
  end
end