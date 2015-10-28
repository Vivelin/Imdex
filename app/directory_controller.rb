module Imdex
  class DirectoryController
    attr_reader :directory

    def initialize(directory, settings)
      @directory = directory
      @settings = settings
    end

    def name
      @directory.name
    end

    def count
      @directory.count
    end

    def tile_src
      unless @directory.images.empty?
        tile_image = @directory.images.first
        "#{ @directory.name }/#{ tile_image }"
      end
    end

    def directories
      @directory.directories.map do |item|
        item_path = File.expand_path(item, @directory.path)
        model = Imdex::Directory.new(item_path)
        
        DirectoryController.new(model, @settings)
      end
    end

    def files
      @directory.files
    end

    def images
      @directory.images
    end

    def root?
      @directory.path == @settings.basedir
    end
  end
end