module Imdex
  class UserController
    def initialize(session, config)
      @session = session
      @config = config
    end

    def valid?
      not @session[:user].empty?
    end

    def user
      @session[:user]
    end

    def name
      @session[:name]
    end

    def avatar
      @session[:avatar]
    end

    def role
      @config[user] || :guest
    end

    def owner?
      role == :owner
    end

    def admin?
      owner? || role == :admin
    end

    def recognized?
      admin? || role == :recognized
    end
  end
end