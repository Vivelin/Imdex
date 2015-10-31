module Imdex
  class DirectoryController
    attr_reader :directory

    def initialize(directory, user, settings)
      @directory = directory
      @user = user
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
        tile_image = images.first
        "#{ @directory.name }/#{ tile_image }"
      end
    end

    def directories
      dirs = @directory.directories.select { |item| show_dir?(item) }
      dirs.map do |item|
        item_path = File.expand_path(item, @directory.path)
        model = Imdex::Directory.new(item_path)
        
        DirectoryController.new(model, @user, @settings)
      end
    end

    def files
      @directory.files.select { |item| show_file?(item) }
    end

    def images
      @directory.images.select { |item| show_file?(item) }
    end

    def root?
      @directory.path == @settings.basedir
    end

    private
    def show_dir?(item)
      begin
        return @user.recognized? if item =~ /^\./
      rescue ArgumentError
        puts "THIS FILENAME IS FUCKED UP: #{ item }"
      end

      true
    end

    def show_file?(item)
      begin
        return @user.recognized? if item =~ /^\./
      rescue ArgumentError
        puts "THIS FILENAME IS FUCKED UP: #{ item }"
      end

      true
    end
  end
end