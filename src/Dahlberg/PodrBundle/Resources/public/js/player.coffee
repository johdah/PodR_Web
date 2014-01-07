class Player
  audio = new Audio
  currentEpisode = -1
  timeSinceLastUpdate = 0;

  constructors: ->

  loadPlayer: ->
    console.log "Player - Loading"

    $(".play-btn").on "click", ->
      id = $(this).attr('id').replace(/[^0-9]/g, '')
      console.log "Playing episode " + id
      loadEpisode id

    # Forward
    $("#player-forward").on "click", (e) ->
      e.preventDefault()
      forwardAudio()

    $("#player-fast-forward").on "click", (e) ->
      e.preventDefault()
      fastForwardAudio()

    # Play/Pause
    $("#player-pause").on "click", (e) ->
      e.preventDefault()
      pauseAudio()

    $("#player-play").on "click", (e) ->
      e.preventDefault()
      playAudio()

    # Rewind
    $("#player-fast-rewind").on "click", (e) ->
      e.preventDefault()
      fastRewindAudio()

    $("#player-rewind").on "click", (e) ->
      e.preventDefault()
      rewindAudio()

    # Stop
    $("#player-stop").on "click", (e) ->
      e.preventDefault()
      stopAudio()

  # Events
  timeUpdate = ->
    currentTime = parseInt(audio.currentTime, 10)
    maxTime = audio.duration
    currentPercentage = parseInt((currentTime/maxTime)*101)
    $("#player-seekbar .meter").css "width", currentPercentage + "%"

    @timeSinceLastTimeUpdate++
    if @timeSinceLastTimeUpdate > 60
      @timeSinceLastTimeUpdate = 0
      updateTimeOnServer()

  # API Functions
  loadEpisode = (id) ->
    currentEpisode = id
    jqxhr = $.getJSON("/api/v1/episode/" + id, ->
    ).done((data)->
      console.log "Loading: " + data["episode"]["enclosureUrl"]
      audio.setAttribute "src", data["episode"]["enclosureUrl"]
      audio.setAttribute "type", data["episode"]["enclosureType"]
    ).fail(->
      console.log "Player - Error occured while loading episode"
    ).always(->
      audio.load()

      # Event: TimeUpdate
      $(audio).bind "timeupdate", ->
        timeUpdate()

      playAudio()
    )

  updateCurrentTimeOnServer = ->
    # API Call: Archive episode
    jqxhr = $.post("/api/v1/userepisode/patch/" + currentEpisode,
      currentPosition: parseInt(audio.currentTime, 10)
      , ->
    ).done((data)->
      console.log "Player - Current time updated with: " + data["currentPosition"]
    ).fail(->
      console.log "Player - Error occured while updating current time"
    ).always(->
    )

  # Actions
  fastRewindAudio = ->
    audio.currentTime -= 60.0

  fastForwardAudio = ->
    audio.currentTime += 60.0

  forwardAudio = ->
    audio.currentTime += 10.0

  rewindAudio = ->
    audio.currentTime -= 10.0

  pauseAudio = ->
    audio.pause()
    console.log "Player - Pause"
    $("#player-pause").hide()
    $("#player-play").show()
    updateCurrentTimeOnServer()

  playAudio = ->
    audio.play()
    console.log "Player - Play"
    $("#player-pause").show()
    $("#player-play").hide()

  stopAudio = ->
    audio.pause()
    audio.currentTime = 0
    console.log "Player - Stop"
    $("#player-pause").hide()
    $("#player-play").show()
    updateCurrentTimeOnServer()

$(document).ready ->
  player = new Player
  player.loadPlayer()